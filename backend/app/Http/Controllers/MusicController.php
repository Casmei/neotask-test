<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\UseCases\AddMusicUseCase;
use App\Domain\UseCases\ListTopMusicsUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddMusicRequest;
use App\Http\Resources\MusicResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Music management
 *
 * APIs for managing Music
 */
class MusicController extends Controller
{
    private ListTopMusicsUseCase $listTopMusicsUseCase;
    private AddMusicUseCase $addMusicUseCase;

    public function __construct(
        ListTopMusicsUseCase $listTopMusicsUseCase,
        AddMusicUseCase $addMusicUseCase
    ) {
        $this->listTopMusicsUseCase = $listTopMusicsUseCase;
        $this->addMusicUseCase = $addMusicUseCase;
    }

    /**
     * Get list of top musics
     */
    public function index(): AnonymousResourceCollection
    {
        $result = $this->listTopMusicsUseCase->execute();

        return MusicResource::collection($result["musics"]);
    }

    /**
     * Add a new music
     */
    public function store(AddMusicRequest $request)
    {
        $music = $this->addMusicUseCase->execute($request);

        return response()->json(
            new MusicResource($music),
            Response::HTTP_CREATED
        );
    }
}
