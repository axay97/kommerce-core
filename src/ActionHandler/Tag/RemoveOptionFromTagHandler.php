<?php
namespace inklabs\kommerce\ActionHandler\Tag;

use inklabs\kommerce\Action\Tag\RemoveOptionFromTagCommand;
use inklabs\kommerce\EntityRepository\OptionRepositoryInterface;
use inklabs\kommerce\EntityRepository\TagRepositoryInterface;
use inklabs\kommerce\Lib\Authorization\AuthorizationContextInterface;
use inklabs\kommerce\Lib\Command\CommandHandlerInterface;

final class RemoveOptionFromTagHandler implements CommandHandlerInterface
{
    /** @var RemoveOptionFromTagCommand */
    private $command;

    /** @var TagRepositoryInterface */
    private $tagRepository;

    /** @var OptionRepositoryInterface */
    private $optionRepository;

    public function __construct(
        RemoveOptionFromTagCommand $command,
        TagRepositoryInterface $tagRepository,
        OptionRepositoryInterface $optionRepository
    ) {
        $this->command = $command;
        $this->tagRepository = $tagRepository;
        $this->optionRepository = $optionRepository;
    }

    public function verifyAuthorization(AuthorizationContextInterface $authorizationContext): void
    {
        $authorizationContext->verifyIsAdmin();
    }

    public function handle()
    {
        $option = $this->optionRepository->findOneById($this->command->getOptionId());
        $tag = $this->tagRepository->findOneById($this->command->getTagId());
        $tag->removeOption($option);

        $this->tagRepository->update($tag);
    }
}
