<?php
/**
 * This file is part of riesenia/pohoda package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Pohoda\IntDoc;

use Riesenia\Pohoda\Agenda;
use Riesenia\Pohoda\Common\OptionsResolver;
use Riesenia\Pohoda\Type\CurrencyItem;
use Riesenia\Pohoda\Type\StockItem;

class Item extends Agenda
{
    /** @var string[] */
    protected $_refElements = ['typeServiceMOSS', 'accounting', 'classificationVAT', 'classificationKVDPH', 'centre', 'activity', 'contract'];

    /** @var string[] */
    protected $_elements = ['text', 'quantity', 'unit', 'coefficient', 'payVAT', 'rateVAT', 'percentVAT', 'discountPercentage', 'homeCurrency', 'foreignCurrency', 'typeServiceMOSS', 'note', 'code', 'symPar', 'accounting', 'classificationVAT', 'classificationKVDPH', 'PDP', 'CodePDP', 'centre', 'activity', 'contract'];

    /**
     * {@inheritdoc}
     */
    public function __construct(array $data, string $ico, bool $resolveOptions = true)
    {
        // process home currency
        if (isset($data['homeCurrency'])) {
            $data['homeCurrency'] = new CurrencyItem($data['homeCurrency'], $ico, $resolveOptions);
        }
        // process foreign currency
        if (isset($data['foreignCurrency'])) {
            $data['foreignCurrency'] = new CurrencyItem($data['foreignCurrency'], $ico, $resolveOptions);
        }
        // process stock item
        if (isset($data['stockItem'])) {
            $data['stockItem'] = new StockItem($data['stockItem'], $ico, $resolveOptions);
        }

        parent::__construct($data, $ico, $resolveOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function getXML(): \SimpleXMLElement
    {
        $xml = $this->_createXML()->addChild('int:intDocItem', '', $this->_namespace('int'));

        $this->_addElements($xml, \array_merge($this->_elements, ['parameters']), 'int');

        return $xml;
    }

    /**
     * {@inheritdoc}
     */
    protected function _configureOptions(OptionsResolver $resolver)
    {
        // available options
        $resolver->setDefined($this->_elements);

        // validate / format options
        $resolver->setNormalizer('text', $resolver->getNormalizer('string90'));
        $resolver->setNormalizer('quantity', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('unit', $resolver->getNormalizer('string10'));
        $resolver->setNormalizer('coefficient', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('payVAT', $resolver->getNormalizer('bool'));
        $resolver->setAllowedValues('rateVAT', ['none', 'high', 'low', 'third', 'historyHigh', 'historyLow', 'historyThird']);
        $resolver->setNormalizer('percentVAT', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('discountPercentage', $resolver->getNormalizer('float'));
        $resolver->setNormalizer('note', $resolver->getNormalizer('string90'));
        $resolver->setNormalizer('code', $resolver->getNormalizer('string64'));
        $resolver->setNormalizer('symPar', $resolver->getNormalizer('string20'));
        $resolver->setNormalizer('PDP', $resolver->getNormalizer('bool'));
        $resolver->setNormalizer('CodePDP', $resolver->getNormalizer('string4'));
    }
}