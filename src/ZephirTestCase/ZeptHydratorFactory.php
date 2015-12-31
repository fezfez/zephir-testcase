<?php

namespace ZephirTestCase;

class ZeptHydratorFactory
{
    /**
     * @return \ZephirTestCase\ZeptHydrator
     */
    public static function getInstance()
    {
        return new ZeptHydrator(CodeRunnerFactory::getInstance());
    }
}
