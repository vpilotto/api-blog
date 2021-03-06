<?php

namespace Test\Domain\Service;

use App\Domain\DTO\AtualizaPostDTO;
use App\Domain\Entity\Post;
use App\Domain\Entity\User;
use App\Domain\Exception\UserNaoAutorizadoException;
use App\Domain\Repository\PostRepositoryInterface;
use App\Domain\Service\AtualizarPost;
use App\Domain\ValueObject\PostId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function random_int;

class AtualizarPostTest extends TestCase
{
    private MockObject $postRepository;
    private MockObject $atualizaPostDto;
    private MockObject $post;
    private MockObject $user;
    private AtualizarPost $atualizarPost;

    public function setUp(): void
    {
        $this->postRepository  = $this->getMockForAbstractClass(PostRepositoryInterface::class);
        $this->atualizaPostDto = $this->getMockBuilder(AtualizaPostDTO::class)
            ->disableOriginalConstructor()->getMock();
        $this->post            = $this->getMockBuilder(Post::class)
            ->disableOriginalConstructor()->getMock();
        $this->user            = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()->getMock();

        $this->atualizarPost = new AtualizarPost($this->postRepository);
    }

    /** @test */
    public function atualizarDeveFuncionar(): void
    {
        $params = [
            'postId'  => random_int(1, 999),
            'title'   => 'Latest updates, August 1st',
            'content' => 'The whole text for the blog post goes here in this key',
            'user'    => $this->user,
        ];

        $this->atualizaPostDto
            ->expects(self::once())
            ->method('getPostId')
            ->willReturn($params['postId']);

        $this->atualizaPostDto
            ->expects(self::once())
            ->method('getTitle')
            ->willReturn($params['title']);

        $this->atualizaPostDto
            ->expects(self::once())
            ->method('getContent')
            ->willReturn($params['content']);

        $this->atualizaPostDto
            ->expects(self::once())
            ->method('getUser')
            ->willReturn($params['user']);

        $this->post
            ->expects(self::once())
            ->method('user')
            ->willReturn($this->user);

        $this->postRepository
            ->expects(self::once())
            ->method('getById')
            ->with(PostId::fromInt($params['postId']))
            ->willReturn($this->post);

        $this->postRepository
            ->expects(self::once())
            ->method('store');

        $this->atualizarPost->atualizar($this->atualizaPostDto);
    }

    /** @test */
    public function atualizaDeveRetornarExceptionSeUsuarioDiferenteDoAutor(): void
    {
        $this->expectException(UserNaoAutorizadoException::class);

        $params = [
            'postId' => random_int(1, 999),
            'user'   => $this->getMockBuilder(User::class)->disableOriginalConstructor()->getMock(),
        ];

        $this->atualizaPostDto
            ->expects(self::once())
            ->method('getPostId')
            ->willReturn($params['postId']);

        $this->atualizaPostDto
            ->expects(self::once())
            ->method('getUser')
            ->willReturn($params['user']);

        $this->post
            ->expects(self::once())
            ->method('user')
            ->willReturn($this->user);

        $this->postRepository
            ->expects(self::once())
            ->method('getById')
            ->with(PostId::fromInt($params['postId']))
            ->willReturn($this->post);

        $this->atualizarPost->atualizar($this->atualizaPostDto);
    }
}
