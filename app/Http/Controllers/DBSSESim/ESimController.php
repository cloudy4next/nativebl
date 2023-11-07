<?php

namespace App\Http\Controllers\DBSSESim;

use NativeBL\Controller\AbstractNativeController as AbstractController;
use App\Contracts\DBSSESim\ESimRepositoryInterface;
use App\Contracts\Services\DBSSESim\ESimServiceInterface;
use NativeBL\Field\Field;
use NativeBL\Field\TextField;
use Illuminate\Http\Request;

class ESimController extends AbstractController
{
    public function __construct(private ESimServiceInterface $esimService, private ESimRepositoryInterface $esimRepository)
    {
        $this->esimService = $esimService;
        $this->esimRepository = $esimRepository;
    }

    public function getRepository()
    {

        return $this->esimRepository;
    }

    public function configureForm()
    {
        return [];
    }

    public function configureFilter(): void
    {

        $fields = [
            TextField::init('msisdn', 'MSISDN'),
        ];
        $this->getFilter($fields);
    }


    public function configureActions(): iterable
    {
        return [];
    }



    public function show()
    {
        dd($this->esimService->getESimCollection(123));
    }

    public function esim(Request $request)
    {
        $this->initGrid(
            [
                Field::init('msisdn'),
                Field::init('icc'),
                Field::init('status'),
                Field::init('payment-type'),
                Field::init('contract-id'),
            ]
        );

        // $msidsnFilter = $request->input('filters.msisdn');
        // $data = $msidsnFilter == null ? [] : $this->esimService->getESimCollection($msidsnFilter);
        return view('home.e-sim.e-sim');
        // ->with('data', $data);

    }


}
