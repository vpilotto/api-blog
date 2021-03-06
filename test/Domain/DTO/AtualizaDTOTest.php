<?php

namespace Test\Domain\DTO;

use App\Domain\DTO\AtualizaPostDTO;
use App\Domain\Entity\User;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AtualizaDTOTest extends TestCase
{
    /** @test */
    public function fromArrayDeveFuncionar(): void
    {
        $user          = $this->getMockBuilder(User::class)->disableOriginalConstructor()->getMock();
        $defaultParams = [
            'postId'  => random_int(1, 999),
            'title'   => 'Latest updates, August 1st',
            'content' => 'The whole text for the blog post goes here in this key',
            'user'    => $user,
        ];

        $cadastraPostDto = AtualizaPostDTO::fromArray($defaultParams);

        self::assertSame($defaultParams['postId'], $cadastraPostDto->getPostId());
        self::assertSame($defaultParams['title'], $cadastraPostDto->getTitle());
        self::assertSame($defaultParams['content'], $cadastraPostDto->getContent());
        self::assertSame($user, $cadastraPostDto->getUser());
    }

    /**
     * @test
     * @dataProvider providerDeErros
     */
    public function fromArrayDeveFalhar(array $data, string $exception): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($exception);

        AtualizaPostDTO::fromArray($data);
    }

    public function providerDeErros(): array
    {
        $defaultParams = [
            'postId'  => random_int(1, 999),
            'title'   => 'Latest updates, August 1st',
            'content' => 'The whole text for the blog post goes here in this key',
            'userId'  => $this->getMockBuilder(User::class)->disableOriginalConstructor()->getMock(),
        ];

        $titleNaoEnviado = $defaultParams;
        unset($titleNaoEnviado['title']);

        $contentNaoEnviado = $defaultParams;
        unset($contentNaoEnviado['content']);

        $substituirValor = static function ($chave, $valor) use ($defaultParams) {
            return array_merge($defaultParams, [$chave => $valor]);
        };

        return [
            'fromArrayDeveFalharSePostIdDoTipoErrado'   => [
                'data'             => $substituirValor('postId', 'string'),
                'exceptionMessage' => '"postId" needs to be integer',
            ],
            'fromArrayDeveFalharSePostIdForNull'        => [
                'data'             => $substituirValor('postId', null),
                'exceptionMessage' => '"postId" needs to be integer',
            ],
            'fromArrayDeveFalharSeTitleForNull'         => [
                'data'             => $substituirValor('title', null),
                'exceptionMessage' => '"title" is required',
            ],
            'fromArrayDeveFalharSeTitleForVazio'        => [
                'data'             => $substituirValor('title', ''),
                'exceptionMessage' => '"title" is required',
            ],
            'fromArrayDeveFalharSeTitleNaoForEnviado'   => [
                'data'             => $titleNaoEnviado,
                'exceptionMessage' => '"title" is required',
            ],
            'fromArrayDeveFalharSeContentForNull'       => [
                'data'             => $substituirValor('content', null),
                'exceptionMessage' => '"content" is required',
            ],
            'fromArrayDeveFalharSeContentForVazio'      => [
                'data'             => $substituirValor('content', ''),
                'exceptionMessage' => '"content" is required',
            ],
            'fromArrayDeveFalharSeContentNaoForEnviado' => [
                'data'             => $contentNaoEnviado,
                'exceptionMessage' => '"content" is required',
            ],
        ];
    }
}
