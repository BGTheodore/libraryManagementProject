<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEmpruntRequest;
use App\Http\Requests\UpdateEmpruntRequest;
use App\Repositories\EmpruntRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Models\Emprunt;
use App\Models\User;
use App\Models\Livre;

class EmpruntController extends AppBaseController
{
    /** @var  EmpruntRepository */
    private $empruntRepository;

    public function __construct(EmpruntRepository $empruntRepo)
    {
        $this->empruntRepository = $empruntRepo;
    }

    /**
     * Display a listing of the Emprunt.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $emprunts = $this->empruntRepository->all();

        return view('emprunts.index')
            ->with('emprunts', $emprunts);
    }

    public static function verifier_existence_utilisateur_pour_creation_emprunt($id)
    {
        $user = User::where("id",$id)->count();

        return $user;
    }

    public static function verifier_existence_livre_pour_creation_emprunt($id)
    {
        $livre = Livre::where("id",$id)->count();

        return $livre;
    }

    /**
     * Show the form for creating a new Emprunt.
     *
     * @return Response
     */
    public function create()
    {
        $abonnes = User::all();
        $ouvrages = Livre::all();
        return view('emprunts.create', compact(['abonnes','ouvrages']));
    }

    /**
     * Store a newly created Emprunt in storage.
     *
     * @param CreateEmpruntRequest $request
     *
     * @return Response
     */
    public function store(CreateEmpruntRequest $request)
    {
        $input = $request->all();
        if ($this->verifier_existence_utilisateur_pour_creation_emprunt($request->id_utilisateur) == 0) 
        {
            Flash::error('Cet utilisateur n\'existe pas');
            return redirect()->back()->withInput();
        }

        if ($this->verifier_existence_livre_pour_creation_emprunt($request->id_livre) == 0) 
        {
            Flash::error('Ce livre n\'existe pas');
            return redirect()->back()->withInput();
        }
        

        $emprunt = $this->empruntRepository->create($input);

        Flash::success('Emprunt enregistre avec succes !');

        return redirect(route('emprunts.index'));
    }

    /**
     * Display the specified Emprunt.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $emprunt = $this->empruntRepository->find($id);

        if (empty($emprunt)) {
            Flash::error('Emprunt non trouve !');

            return redirect(route('emprunts.index'));
        }

        return view('emprunts.show')->with('emprunt', $emprunt);
    }

    /**
     * Show the form for editing the specified Emprunt.
     *
     * @param int $idgit commit -m 'Added the Emprunt
     *
     * @return Response
     */
    public function edit($id)
    {
        $emprunt = $this->empruntRepository->find($id);
        $abonnes = User::all();
        $ouvrages = Livre::all();

        if (empty($emprunt)) {
            Flash::error('Emprunt non trouve !');

            return redirect(route('emprunts.index'));
        }

        return view('emprunts.edit', compact(['abonnes','ouvrages','emprunt']));
    }

    /**
     * Update the specified Emprunt in storage.
     *
     * @param int $id
     * @param UpdateEmpruntRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEmpruntRequest $request)
    {
        $emprunt = $this->empruntRepository->find($id);

        if (empty($emprunt)) {
            Flash::error('Emprunt non trouve !');

            return redirect(route('emprunts.index'));
        }

        $emprunt = $this->empruntRepository->update($request->all(), $id);

        Flash::success('Emprunt modifie avec succes !');

        return redirect(route('emprunts.index'));
    }

    /**
     * Remove the specified Emprunt from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $emprunt = $this->empruntRepository->find($id);

        if (empty($emprunt)) {
            Flash::error('Emprunt non trouve !');

            return redirect(route('emprunts.index'));
        }

        $this->empruntRepository->delete($id);

        Flash::success('Emprunt supprime avec succes !');

        return redirect(route('emprunts.index'));
    }
}
