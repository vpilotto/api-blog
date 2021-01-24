<?php

namespace App\Domain\Entity;

use App\Domain\ValueObject\Content;
use App\Domain\ValueObject\PostId;
use App\Domain\ValueObject\Published;
use App\Domain\ValueObject\Title;
use App\Domain\ValueObject\Updated;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Posts")
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\Column(
     *     name="id",
     *     type="App\Domain\ValueObject\PostId"
     * )
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private PostId $id;

    /**
     * @ORM\Column(
     *     name="title",
     *     type="App\Domain\ValueObject\Title"
     * )
     */
    private Title $title;

    /**
     * @ORM\Column(
     *     name="content",
     *     type="App\Domain\ValueObject\Content"
     * )
     */
    private Content $content;

    /**
     * @ORM\Column(
     *     name="published",
     *     type="App\Domain\ValueObject\Published"
     * )
     */
    private Published $published;

    /**
     * @ORM\Column(
     *     name="updated",
     *     type="App\Domain\ValueObject\Updated",
     *     nullable=true
     * )
     */
    private ?Updated $updated;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id")
     */
    private User $user;

    public function title(): Title
    {
        return $this->title;
    }

    public function content(): Content
    {
        return $this->content;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function jsonSerialize(): array
    {
        return [
            'title' => $this->title()->toString(),
            'content' => $this->content()->toString(),
            'user' => $this->user()->id()->value()
        ];
    }

    public static function novo(
        Title $title,
        Content $content,
        User $user
    ): self {
        $instance = new self();

        $instance->title = $title;
        $instance->content = $content;
        $instance->user = $user;
        $instance->published = Published::agora();
        $instance->updated = null;

        return $instance;
    }
}
