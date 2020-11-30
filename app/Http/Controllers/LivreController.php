<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLivreRequest;
use App\Http\Requests\UpdateLivreRequest;
use App\Repositories\LivreRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class LivreController extends AppBaseController
{
    /** @var  LivreRepository */
    private $livreRepository;

    public function __construct(LivreRepository $livreRepo)
    {
        $this->livreRepository = $livreRepo;
    }

    /**
     * Display a listing of the Livre.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $livres = $this->livreRepository->all();

        return view('livres.index')
            ->with('livres', $livres);
    }

    /**
     * Show the form for creating a new Livre.
     *
     * @return Response
     */
    public function create()
    {
        return view('livres.create');
    }

    /**
     * Store a newly created Livre in storage.
     *
     * @param CreateLivreRequest $request
     *
     * @return Response
     */
    public function store(CreateLivreRequest $request)
    {
        $input = $request->all();

        $livre = $this->livreRepository->create($input);

        Flash::success('Livre saved successfully.');

        return redirect(route('livres.index'));
    }

    /**
     * Display the specified Livre.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $livre = $this->livreRepository->find($id);

        if (empty($livre)) {
            Flash::error('Livre not found');

            return redirect(route('livres.index'));
        }

        return view('livres.show')->with('livre', $livre);
    }

    /**
     * Show the form for editing the specified Livre.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $livre = $this->livreRepository->find($id);

        if (empty($livre)) {
            Flash::error('Livre not found');

            return redirect(route('livres.index'));
        }

        return view('livres.edit')->with('livre', $livre);
    }

    /**
     * Update the specified Livre in storage.
     *
     * @param int $id
     * @param UpdateLivreRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLivreRequest $request)
    {
        $livre = $this->livreRepository->find($id);

        if (empty($livre)) {
            Flash::error('Livre not found');

            return redirect(route('livres.index'));
        }

        $livre = $this->livreRepository->update($request->all(), $id);

        Flash::success('Livre updated successfully.');

        return redirect(route('livres.index'));
    }

    /**
     * Remove the specified Livre from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $livre = $this->livreRepository->find($id);

        if (empty($livre)) {
            Flash::error('Livre not found');

            return redirect(route('livres.index'));
        }

        $this->livreRepository->delete($id);

        Flash::success('Livre deleted successfully.');

        return redirect(route('livres.index'));
    }
}