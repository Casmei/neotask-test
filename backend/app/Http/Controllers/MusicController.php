<?php

namespace App\Http\Controllers;

use App\Domain\UseCases\ListTopMusicsUseCase;
use App\Http\Controllers\Controller;
use App\Http\Resources\MusicResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MusicController extends Controller
{
    private ListTopMusicsUseCase $listTopMusicsUseCase;

    public function __construct(ListTopMusicsUseCase $listTopMusicsUseCase)
    {
        $this->listTopMusicsUseCase = $listTopMusicsUseCase;
    }

    /**
     * Get list of top musics
     */
    public function index(): AnonymousResourceCollection
    {
        $result = $this->listTopMusicsUseCase->execute();

        return MusicResource::collection($result["musics"]);
    }
}
