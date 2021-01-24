<?php

namespace App\Domain\DTO;

use App\Domain\ValueObject\UserId;
use Assert\Assertion;

class CadastraPostDTO
{
    private function __construct(
        private string $title,
        private string $content,
        private UserId $userId
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public static function fromArray(array $params): self
    {
        Assertion::keyIsset($params, 'title', '"title" is required');
        Assertion::notEmpty($params['title'], '"title" is required');

        Assertion::keyIsset($params, 'content', '"content" is required');
        Assertion::notEmpty($params['content'], '"content" is required');

        return new self(
            $params['title'],
            $params['content'],
            $params['userId']
        );
    }
}