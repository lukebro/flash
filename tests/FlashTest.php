<?php

use Lukebro\Flash\Flash;
use Mockery as m;

class FlashTest extends PHPUnit_Framework_TestCase
{

	protected $session;

	function tearDown()
	{
		m::close();
	}

	function setUp()
	{
		$this->session = m::mock('Lukebro\Flash\FlashStoreInterface', ['has' => false]);
	}

	/** @test */
	function it_loads_from_the_session()
	{
		$data = [
			'level' => 'success',
			'message' => 'You have logged in!'
		];
		
		$this->session->shouldReceive('has')->andReturn(true);
		$this->session->shouldReceive('get')->with('flash_message')->andReturn($data);

		$flash = new Flash($this->session);

		$this->assertEquals($flash->level, 'success');
		$this->assertEquals($flash->message, 'You have logged in!');
	}

	/** @test */
	function it_flashes_to_the_session()
	{
		$this->session->shouldReceive('has')->andReturn(false);
		$flash = new Flash($this->session);

		$data = [
			'level' => 'laraveliscool',
			'message' => 'Laravel is really cool.'
		];

		$this->session->shouldReceive('has')->with($flash->key);
		$this->session->shouldReceive('flash')->with($flash->key, $data);

		$flash->create($data['message'], $data['level']);
	}

	/** @test */
	function it_creates_a_flash()
	{
		$flash = new Flash($this->session);

		$data = [
			'level' => 'testingmagic',
			'message' => 'You won the lottery!'
		];

		$this->session->shouldReceive('flash')->with($flash->key, $data);

		$flash->testingmagic('You won the lottery!');
	}

	/** @test */
	function it_returns_true_when_flash_exists()
	{
		$data = [
			'level' => 'drdre',
			'message' => 'Beats are cool.'
		];

		$this->session->shouldReceive('has')->andReturn(true);
		$this->session->shouldReceive('get')->andReturn($data);

		$flash = new Flash($this->session);

		$this->assertTrue($flash->exists());
		$this->assertTrue($flash->has($data['level']));
	}

	/** @test */
	function it_returns_false_when_flash_does_not_exist()
	{
		$flash = new Flash($this->session);

		$this->assertFalse($flash->exists());
		$this->assertFalse($flash->has('drdre'));
	}

}