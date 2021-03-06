<?php

/*
 * Copyright (C) 2009-2016 Internet Neutral Exchange Association Company Limited By Guarantee.
 * All Rights Reserved.
 *
 * This file is part of IXP Manager.
 *
 * IXP Manager is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation, version v2.0 of the License.
 *
 * IXP Manager is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for
 * more details.
 *
 * You should have received a copy of the GNU General Public License v2.0
 * along with IXP Manager.  If not, see:
 *
 * http://www.gnu.org/licenses/gpl-2.0.html
 */

use Entities\Switcher;

use IXP\Tasks\Yaml\SwitchConfigurationGenerator as SwitchConfigurationGenerator;

use Illuminate\Foundation\Testing\WithoutMiddleware;

/**
 * PHPUnit test class to test the configuration generation of yaml switch configurations
 * against known good configurations.
 *
 * @author     Barry O'Donovan <barry@islandbridgenetworks.ie>
 * @category   Tests
 * @copyright  Copyright (C) 2009-2017 Internet Neutral Exchange Association Company Limited By Guarantee
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL V2.0
 */
class GenerateYamlTest extends TestCase
{
    public $names        = [ 'switch1', 'switch2' ];
    public $ids          = [ 1, 2 ];

    public function testYamlByIdGeneration()
    {
        foreach( $this->ids as $id ) {
            $s = D2EM::getRepository('Entities\Switcher')->find($id);
            /** @var Switcher $s */

            $conf = ( new SwitchConfigurationGenerator( $s ) )->render();

            $knownGoodConf = file_get_contents( base_path() . "/data/travis-ci/known-good/ci-apiv4-yaml-{$id}.yaml" );
            $this->assertFalse( $knownGoodConf === false, "YAML Switch Configuration - could not load known good file ci-apiv4-yaml-{$id}.yaml" );

            $this->assertEquals( $knownGoodConf, $conf, "Known good and generated RC configuration for switch ID:{$id} do not match" );
        }
    }

    public function testYamlByNameGeneration()
    {
        foreach( $this->names as $name ) {
            $s = D2EM::getRepository('Entities\Switcher')->findOneBy(['name'=>$name]);
            /** @var Switcher $s */

            $conf = ( new SwitchConfigurationGenerator( $s ) )->render();

            $knownGoodConf = file_get_contents( base_path() . "/data/travis-ci/known-good/ci-apiv4-yaml-{$name}.yaml" );
            $this->assertFalse( $knownGoodConf === false, "YAML Switch Configuration - could not load known good file ci-apiv4-yaml-{$name}.yaml" );

            $this->assertEquals( $knownGoodConf, $conf, "Known good and generated RC configuration for switch {$name} do not match" );
        }
    }

}
