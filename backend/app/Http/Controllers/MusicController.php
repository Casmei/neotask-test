<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\UseCases\AddMusicUseCase;
use App\Domain\UseCases\ListApprovedMusicsUseCase;
use App\Domain\UseCases\ListPendingMusicsUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddMusicRequest;
use App\Http\Requests\ListMusicsRequest;
use App\Http\Resources\MusicResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Music management
 *
 * APIs for managing Music
 */
class MusicController extends Controller
{
    private ListApprovedMusicsUseCase $listApprovedMusicsUseCase;
    private ListPendingMusicsUseCase $listPendingMusicsUseCase;
    private AddMusicUseCase $addMusicUseCase;

    public function __construct(
        ListApprovedMusicsUseCase $listApprovedMusicsUseCase,
        ListPendingMusicsUseCase $listPendingMusicsUseCase,
        AddMusicUseCase $addMusicUseCase
    ) {
        $this->listApprovedMusicsUseCase = $listApprovedMusicsUseCase;
        $this->addMusicUseCase = $addMusicUseCase;
        $this->listPendingMusicsUseCase = $listPendingMusicsUseCase;
    }

    /**
     * Get list of top musics
     *
     * @unauthenticated
     */
    public function index(
        ListMusicsRequest $request
    ): AnonymousResourceCollection {
        $result = $this->listApprovedMusicsUseCase->execute(
            $request->validated()
        );

        return MusicResource::collection($result["musics"])->additional([
            "meta" => [
                "total" => $result["total"],
                "current_page" => $result["current_page"],
                "per_page" => $result["per_page"],
                "last_page" => $result["last_page"],
            ],
        ]);
    }

    /**
     * Add a new music
     */
    public function store(AddMusicRequest $request)
    {
        $music = $this->addMusicUseCase->execute($request->validated());

        return response()->json(
            new MusicResource($music),
            Response::HTTP_CREATED
        );
    }

    /**
     * Get list of pending musics
     */
    public function pending(
        ListMusicsRequest $request
    ): AnonymousResourceCollection {
        $result = $this->listPendingMusicsUseCase->execute(
            $request->validated()
        );

        return MusicResource::collection($result["musics"])->additional([
            "meta" => [
                "total" => $result["total"],
                "current_page" => $result["current_page"],
                "per_page" => $result["per_page"],
                "last_page" => $result["last_page"],
            ],
        ]);
    }
}
