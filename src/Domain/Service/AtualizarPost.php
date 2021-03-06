<?php

namespace App\Domain\Service;

use App\Domain\DTO\AtualizaPostDTO;
use App\Domain\Entity\Post;
use App\Domain\Entity\User;
use App\Domain\Exception;
use App\Domain\Repository\PostRepositoryInterface;
use App\Domain\ValueObject\Content;
use App\Domain\ValueObject\PostId;
use App\Domain\ValueObject\Title;

class AtualizarPost
{
    private PostRepositoryInterface $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function atualizar(AtualizaPostDTO $atualizaDto): Post
    {
        $post = $this->postRepository->getById(PostId::fromInt($atualizaDto->getPostId()));
        $this->verificaSeAutorEstaEditando($post, $atualizaDto->getUser());

        $post->atualiza(
            Title::fromString($atualizaDto->getTitle()),
            Content::fromString($atualizaDto->getContent())
        );

        $this->postRepository->store($post);

        return $post;
    }

    private function verificaSeAutorEstaEditando(Post $post, User $user): void
    {
        if ($post->user() !== $user) {
            throw Exception\UserNaoAutorizadoException::execute();
        }
    }
}
