<?php

namespace Test\Domain\DTO;

use App\Domain\DTO\CadastraPostDTO;
use App\Domain\ValueObject\UserId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CadastraPostDTOTest extends TestCase
{
    /** @test */
    public function fromArrayDeveFuncionar(): void
    {
        $userId = UserId::fromInt(random_int(1, 999));
        $defaultParams = [
            'title'    => 'Latest updates, August 1st',
            'content' => 'The whole text for the blog post goes here in this key',
            'userId' => $userId
        ];

        $cadastraPostDto = CadastraPostDTO::fromArray($defaultParams);

        self::assertSame($defaultParams['title'], $cadastraPostDto->getTitle());
        self::assertSame($defaultParams['content'], $cadastraPostDto->getContent());
        self::assertSame($userId, $cadastraPostDto->getUserId());
    }

    /**
     * @test
     * @dataProvider providerDeErros
     */
    public function fromArrayDeveFalhar(array $data, string $exception): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($exception);

        CadastraPostDTO::fromArray($data);
    }

    public function providerDeErros(): array
    {
        $defaultParams = [
            'title'    => 'Latest updates, August 1st',
            'content' => 'The whole text for the blog post goes here in this key',
            'userId' => UserId::fromInt(random_int(1, 999))
        ];

        $titleNaoEnviado = $defaultParams;
        unset($titleNaoEnviado['title']);

        $contentNaoEnviado = $defaultParams;
        unset($contentNaoEnviado['content']);

        $substituirValor = static function ($chave, $valor) use ($defaultParams) {
            return array_merge($defaultParams, [$chave => $valor]);
        };

        return [
            'fromArrayDeveFalharSeTitleForNull'                       => [
                'data'             => $substituirValor('title', null),
                'exceptionMessage' => '"title" is required',
            ],
            'fromArrayDeveFalharSeTitleForVazio'                      => [
                'data'             => $substituirValor('title', ''),
                'exceptionMessage' => '"title" is required',
            ],
            'fromArrayDeveFalharSeTitleNaoForEnviado'                 => [
                'data'             => $titleNaoEnviado,
                'exceptionMessage' => '"title" is required',
            ],
            'fromArrayDeveFalharSeContentForNull'                    => [
                'data'             => $substituirValor('content', null),
                'exceptionMessage' => '"content" is required',
            ],
            'fromArrayDeveFalharSeContentForVazio'                   => [
                'data'             => $substituirValor('content', ''),
                'exceptionMessage' => '"content" is required',
            ],
            'fromArrayDeveFalharSeContentNaoForEnviado'              => [
                'data'             => $contentNaoEnviado,
                'exceptionMessage' => '"content" is required',
            ],
        ];
    }
}