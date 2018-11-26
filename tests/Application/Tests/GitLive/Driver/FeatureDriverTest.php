<?php
/**
 * FeatureDriverTest.php
 *
 * @category   GitCommand
 * @package    Git-Live
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @since      2018/11/25
 */

namespace Tests\GitLive\Driver;

use GitLive\Application\Container;
use GitLive\Driver\ConfigDriver;
use GitLive\Driver\FeatureDriver;
use GitLive\GitLive;
use GitLive\Mock\SystemCommand;
use GitLive\Support\SystemCommandInterface;
use Tests\GitLive\TestCase;

use App;

/**
 * Class FeatureDriverTest
 *
 * @category   GitCommand
 * @package Tests\GitLive\Driver
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @since      2018/11/25
 */
class FeatureDriverTest extends TestCase
{



    public function testFeatureTrack()
    {
        $mock = \Mockery::mock(SystemCommand::class);

        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', true)
            ->andReturn('.git');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.ignore', true)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.name', true)
            ->andReturn('');


        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch upstream', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --abbrev-ref HEAD 2>/dev/null', true)
            ->andReturn('feature/unit_testing');

        $mock->shouldReceive('exec')
            ->never()
            ->with('git checkout upstream/feature/unit_testing', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->never()
            ->with('git checkout -b feature/unit_testing', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git pull upstream feature/unit_testing', false)
            ->andReturn('');


        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {

                return $mock;
            }
        );

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featureTrack('feature/unit_testing');

        $this->assertTrue(true);
    }


    public function testFeatureTrackOther()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', true)
            ->andReturn('.git');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.ignore', true)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.name', true)
            ->andReturn('');




        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch upstream', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --abbrev-ref HEAD 2>/dev/null', true)
            ->andReturn('feature/unit_testing_other');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git checkout upstream/feature/unit_testing', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git checkout -b feature/unit_testing', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git pull upstream feature/unit_testing', false)
            ->andReturn('');


        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {

                return $mock;
            }
        );

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featureTrack('feature/unit_testing');

        $this->assertTrue(true);
    }

    public function testFeatureStart()
    {
        $mock = \Mockery::mock(SystemCommand::class);

        $mock->shouldReceive('exec')
            ->with('git rev-parse --git-dir 2> /dev/null', true)
            ->andReturn('.git');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.ignore', true)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.name', true)
            ->andReturn('');


        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true)
            ->andReturn('staging');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch upstream', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false)
            ->andReturn('');
        /*
        $mock->shouldReceive('exec')
            ->once()
            ->with('git symbolic-ref HEAD 2>/dev/null', true)
            ->andReturn('refs/heads/feature/example_1');
        */
        $mock->shouldReceive('exec')
            ->once()
            ->with('git checkout upstream/staging', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git checkout -b feature/unit_testing', false)
            ->andReturn('');


        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {

                return $mock;
            }
        );

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featureStart('unit_testing');

        $this->assertTrue(true);


    }

    public function testFeaturePublish()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', true)
            ->andReturn('.git');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.ignore', true)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.name', true)
            ->andReturn('');


        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch upstream', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git symbolic-ref HEAD 2>/dev/null', true)
            ->andReturn('refs/heads/feature/example_1');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git push upstream refs/heads/feature/example_1', false)
            ->andReturn('');


        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {

                return $mock;
            }
        );

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featurePublish();

        $this->assertTrue(true);

    }


    public function testFeaturePublish_featureignore()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', true)
            ->andReturn('.git');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.ignore', true)
            ->andReturn('true');

        $mock->shouldReceive('exec')
            ->never()
            ->with('git config --get gitlive.branch.feature.prefix.name', true)
            ->andReturn('');


        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch upstream', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git symbolic-ref HEAD 2>/dev/null', true)
            ->andReturn('refs/heads/example_1');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git push upstream refs/heads/example_1', false)
            ->andReturn('');


        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {

                return $mock;
            }
        );

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featurePublish();

        $this->assertTrue(true);

    }

    public function testFeaturePush_nooption()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', true)
            ->andReturn('.git');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.ignore', true)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.name', true)
            ->andReturn('');




        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch upstream', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git symbolic-ref HEAD 2>/dev/null', true)
            ->andReturn('refs/heads/feature/example_1');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git push origin refs/heads/feature/example_1', false)
            ->andReturn('');


        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {

                return $mock;
            }
        );

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featurePush();

        $this->assertTrue(true);

    }


    public function testFeaturePush_withoption1()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', true)
            ->andReturn('.git');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.ignore', true)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.name', true)
            ->andReturn('');




        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch upstream', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git push origin feature/unit_test/example_2', false)
            ->andReturn('');


        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {

                return $mock;
            }
        );

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featurePush('unit_test/example_2');

        $this->assertTrue(true);
    }

    public function testFeaturePush_withoption2()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', true)
            ->andReturn('.git');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.ignore', true)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.name', true)
            ->andReturn('');




        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch upstream', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git push origin feature/example_3', false)
            ->andReturn('');


        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {

                return $mock;
            }
        );

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featurePush('feature/example_3');

        $this->assertTrue(true);
    }


    public function testFeaturePush_withoption3()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', true)
            ->andReturn('.git');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.ignore', true)
            ->andReturn('true');

        $mock->shouldReceive('exec')
            ->never()
            ->with('git config --get gitlive.branch.feature.prefix.name', true)
            ->andReturn('');


        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch upstream', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git push origin example_3', false)
            ->andReturn('');


        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {

                return $mock;
            }
        );

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featurePush('example_3');

        $this->assertTrue(true);
    }

    public function testFeatureList()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            ->with('git rev-parse --git-dir 2> /dev/null', true)
            ->andReturn('.git');

        $mock->shouldReceive('exec')
            ->with('git config --get gitlive.branch.feature.prefix.ignore', true)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->with('git config --get gitlive.branch.feature.prefix.name', true)
            ->andReturn('');


        $mock->shouldReceive('exec')
            ->with('git branch --list "feature/*"', true)
            ->andReturn('feature/hogehoge');


        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {

                return $mock;
            }
        );

        $FeatureDriver = App::make(FeatureDriver::class);

        $res = $FeatureDriver->featureList();

        $this->assertEquals('feature/hogehoge', $res);
    }

    public function testFeatureChange()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            ->with('git rev-parse --git-dir 2> /dev/null', true)
            ->andReturn('.git');

        $mock->shouldReceive('exec')
            ->with('git config --get gitlive.branch.feature.prefix.ignore', true)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->with('git config --get gitlive.branch.feature.prefix.name', true)
            ->andReturn('');


        $mock->shouldReceive('exec')
            ->with('git checkout feature/unit_test_2', false)
            ->andReturn('feature/hogehoge');


        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {

                return $mock;
            }
        );

        $FeatureDriver = App::make(FeatureDriver::class);

        $res = $FeatureDriver->featureChange('unit_test_2');

        $this->assertTrue(true);

    }

    public function testFeaturePull()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', true)
            ->andReturn('.git');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.ignore', true)
            ->andReturn('');


        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.name', true)
            ->andReturn('');


        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch upstream', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git symbolic-ref HEAD 2>/dev/null', true)
            ->andReturn('refs/heads/feature/example_1');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git pull upstream refs/heads/feature/example_1', false)
            ->andReturn('');


        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {

                return $mock;
            }
        );

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featurePull();

        $this->assertTrue(true);

    }
}
