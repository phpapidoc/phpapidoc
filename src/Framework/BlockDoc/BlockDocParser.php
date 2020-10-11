<?php

namespace PhpApiDoc\Framework\BlockDoc;

use PhpApiDoc\Framework\Api\Api;
use PhpApiDoc\Framework\Api\ApiDefine;
use PhpApiDoc\Framework\Api\Parameter;
use PhpApiDoc\Framework\Api\ParameterCollection;
use PhpApiDoc\Framework\Exception\BlockDocException;
use PhpApiDoc\Framework\Exception\InvalidFieldException;
use PhpApiDoc\Support\Preg;

class BlockDocParser
{
    public function parse($block_doc)
    {
        if (!$this->matchIgnore($block_doc)) {

            $api = $this->matchApi($block_doc);
            $apiDefine = $this->matchApiDefine($block_doc);

            if (!empty($api) && !empty($apiDefine)) {
                throw new BlockDocException('此注释同时包含 @api 和 @apiDefine');
            } elseif (!empty($api)) {
                return $this->buildApi($api, $block_doc);
            } elseif (!empty($apiDefine)) {
                return $this->buildApiDefine($apiDefine, $block_doc);
            }
        }

        return false;
    }

    private function buildApi($api, $doc)
    {
        return new Api(
            $api[0],
            isset($api[1]) ? $api[1] : '',
            $this->matchDescription($doc),
            $this->matchAuthor($doc),
            $this->matchVersion($doc),
            $this->matchGroup($doc),
            $this->matchMethod($doc),
            $this->matchUrl($doc),
            $this->matchTags($doc),
            $this->matchUses($doc),
            $this->matchHeaders($doc),
            $this->matchParams($doc),
            $this->matchSuccesses($doc),
            $this->matchErrors($doc)
        );
    }

    private function buildApiDefine($apiDefine, $doc)
    {
        return new ApiDefine(
            $apiDefine,
            $this->matchHeaders($doc),
            $this->matchParams($doc),
            $this->matchSuccesses($doc),
            $this->matchErrors($doc),
            $this->matchTags($doc)
        );
    }

    private function buildParameterCollection($parameters)
    {
        $list = [];
        foreach ($parameters as $item) {
            $object = new Parameter(
                $item['field'],
                $item['type'],
                $item['description'],
                $item['sample'],
                $item['required']
            );

            $list[$object->getKey()] = $object;
        }

        return new ParameterCollection($list);
    }

    /**
     * @param $doc
     * @return bool
     */
    public function matchIgnore($doc)
    {
        $pattern = '/@apiIgnore.*/i';

        return 1 === preg_match($pattern, $doc);
    }

    /**
     * @param $doc
     * @return array
     */
    public function matchApi($doc)
    {
        return Preg::arr($doc, '/@api (.*+)/i');
    }

    /**
     * @param $doc
     * @return string
     */
    public function matchApiDefine($doc)
    {
        return Preg::firstStr($doc, '/@apiDefine (.*+)/i');
    }

    /**
     * @param $doc
     * @return string
     */
    public function matchGroup($doc)
    {
        return Preg::firstStr($doc, '/@apiGroup (.*+)/i');
    }

    /**
     * @param $doc
     * @return string
     */
    public function matchDescription($doc)
    {
        return Preg::str($doc, '/@apiDescription (.*+)/i');
    }

    /**
     * @param $doc
     * @return string
     */
    public function matchAuthor($doc)
    {
        return Preg::str($doc, '/@apiAuthor (.*+)/i');
    }

    /**
     * @param $doc
     * @return string
     */
    public function matchVersion($doc)
    {
        return Preg::firstStr($doc, '/@apiVersion (.*+)/i');
    }

    /**
     * @param $doc
     * @return string
     */
    public function matchMethod($doc)
    {
        return Preg::firstStr($doc, '/@apiMethod (.*+)/i');
    }

    /**
     * @param $doc
     * @return string
     */
    public function matchUrl($doc)
    {
        return Preg::firstStr($doc, '/@apiUrl (.*+)/i');
    }

    /**
     * @param $doc
     * @return array
     */
    public function matchTags($doc)
    {
        return Preg::arr($doc, '/@apiTags (.*+)/i');
    }

    /**
     * @param $doc
     * @return array
     */
    public function matchUses($doc)
    {
        return Preg::arr($doc, '/@apiUse (.*+)/i');
    }

    /**
     * @param $doc
     * @return ParameterCollection
     * @throws InvalidFieldException
     */
    public function matchHeaders($doc)
    {
        return $this->buildParameterCollection(Preg::parameters($doc, '/@apiHeader.*|@apiNullHeader.*/i'));
    }

    /**
     * @param $doc
     * @return ParameterCollection
     * @throws InvalidFieldException
     */
    public function matchParams($doc)
    {
        return $this->buildParameterCollection(Preg::parameters($doc, '/@apiParam.*|@apiNullParam.*/i'));
    }

    /**
     * @param $doc
     * @return ParameterCollection
     * @throws InvalidFieldException
     */
    public function matchSuccesses($doc)
    {
        return $this->buildParameterCollection(Preg::parameters($doc, '/@apiSuccess.*/i'));
    }

    /**
     * @param $doc
     * @return ParameterCollection
     * @throws InvalidFieldException
     */
    public function matchErrors($doc)
    {
        return $this->buildParameterCollection(Preg::parameters($doc, '/@apiError.*/i'));
    }
}
