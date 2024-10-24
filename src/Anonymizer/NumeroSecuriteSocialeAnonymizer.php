<?php

declare(strict_types=1);

namespace DbToolsBundle\PackEnUS\Anonymizer;

use MakinaCorpus\DbToolsBundle\Anonymization\Anonymizer\AbstractAnonymizer;
use MakinaCorpus\DbToolsBundle\Attribute\AsAnonymizer;
use MakinaCorpus\QueryBuilder\Query\Update;

/**
 * Anonymize american social sécurity numbers(SSN).
 */
#[AsAnonymizer(
    name: 'secu',
    pack: 'en-us',
    description: <<<TXT
    Anonymize with a random fictional American social sécurity numbers. Numbers starting with 900 to 999 are not assigned.
    TXT
)]
class NumeroSecuriteSocialeAnonymizer extends AbstractAnonymizer
{
    /**
     * {@inheritdoc}
     */


    public function anonymize(Update $update): void
    {

        $expr = $update->expression();


        $update->set(
            $this->columnName,
            $this->getSetIfNotNullExpression(
                $expr->concat(
                    '9',
                    $expr->lpad($this->getRandomIntExpression(99), 2, '0'),
                    $expr->lpad($this->getRandomIntExpression(99), 2, '0'),
                    $expr->lpad($this->getRandomIntExpression(9999), 4, '0'),
                )
            )
        );


    }
}
