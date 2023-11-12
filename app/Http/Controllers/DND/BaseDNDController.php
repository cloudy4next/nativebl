<?php

namespace App\Http\Controllers\DND;

use App\Contracts\DND\BaseDNDRepositoryInterface;
use App\Models\DND\DNDChannel;
use App\Services\DND\BaseDNDService;
use Illuminate\Http\Request;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use NativeBL\Field\ChoiceField;
use NativeBL\Field\Field;
use NativeBL\Field\InputField;
use NativeBL\Field\TextField;

class BaseDNDController extends AbstractController
{
    private $channel;
    public function __construct(private BaseDNDService $baseDNDService, private BaseDNDRepositoryInterface $baseDNDRepositoryInterface)
    {
        $this->baseDNDService = $baseDNDService;
        $this->baseDNDRepositoryInterface = $baseDNDRepositoryInterface;
        $this->channel = $this->baseDNDService->getChannels();
    }

    public function getRepository()
    {

        return $this->baseDNDRepositoryInterface;
    }

    public function configureForm()
    {
        return [];
    }

    public function configureFilter(): void
    {
        $channelList = $this->getValuePair();
        $fields = [
            InputField::init('msisdn', 'MSISDN', 'number'),
            ChoiceField::init('channel_name', 'Channel Name', choiceType: 'select', choiceList: $channelList)
        ];
        $this->getFilter($fields);
    }
    public function getValuePair()
    {
        $newList = [];
        foreach ($this->channel as $key => $value) {
            $newList[$value] = $value;
        }
        return $newList;
    }

    public function configureActions(): iterable
    {
        return [];
    }




    public function dndShow()
    {
        $this->initGrid(
            [
                Field::init('msisdn'),
                Field::init('created_at', 'Request Date'),
                Field::init('channel_name', 'Channel Type'),
                Field::init('request_type', 'Request Type'),
                Field::init('api_user_id', 'User ID'),


            ]
        );

        return view('home.dnd.basednd')->with('channels', $this->channel);
    }

    public function dndOnOff(Request $request)
    {
        $response = $this->baseDNDService->SetAsOnOff($request->all());

        return to_route('dnd')->with('success', $response);
    }
}
