<?php

declare(strict_types=1);

namespace DbToolsBundle\PackEnUS\Anonymizer;

use MakinaCorpus\DbToolsBundle\Anonymization\Anonymizer\AbstractAnonymizer;
use MakinaCorpus\DbToolsBundle\Attribute\AsAnonymizer;
use MakinaCorpus\QueryBuilder\Query\Update;

/**
 * Anonymize french telephone numbers.
 *
 * This will create phone number with reserved prefixes for fiction and tests:
 * 1 212-555 et 1 213-555
 * - 1 212 555 XXXX
 * - 1 213 555 XXXX

 *
 * Under the hood, it will simple send basic strings such as: 1 212 555 1234 with
 * trailing 0's randomly replaced with something else. Formating may be
 * implemented later.
 *
 * Options are:
 *   - "mode": can be "NY", "LA", 'IL' or 'MA'
 */
#[AsAnonymizer(
    name: 'phone',
    pack: 'en-us',
    description: <<<TXT
    Anonymize with a random fictional American phone number.
    You can choose if you want a "landline" or a "mobile" phone number with option 'mode'
    TXT
)]
class PhoneNumberAnonymizer extends AbstractAnonymizer
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
                    match ($this->options->get('mode', 'NY')) {
                        'NY' => '1 212 555',
                        'CA' => '1 213 555',
                        'IL' => '1 312 555',
                        'MA' => '1 617 555',

                        default => throw new \InvalidArgumentException('"mode" option can be "mobile", "landline"'),
                    },
                    $expr->lpad($this->getRandomIntExpression(9999), 4, '0')
                ),
            )
        );
    }
}
