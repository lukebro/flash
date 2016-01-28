<?php

use Lukebro\Flash\FlashFactory;
use Lukebro\Flash\Flash;
use Mockery as m;

class FlashTest extends PHPUnit_Framework_TestCase
{

	protected $session;

	protected $key = 'flashes';

	function tearDown()
	{
		m::close();
	}

	function setUp()
	{
		$this->session = m::mock('Lukebro\Flash\FlashStoreInterface', ['has' => false]);
	}

	/** @test */
	function it_loads_one_flash_from_the_session()
	{	

		$data = [
			['level' => 'success', 'message' => 'You have logged in!']
		];
		
		$this->session->shouldReceive('has')->andReturn(true);
		$this->session->shouldReceive('get')->with($this->key)->andReturn($data);

		$flash = new FlashFactory($this->session);

		$this->assertEquals($flash->level, 'success');
		$this->assertEquals($flash->message, 'You have logged in!');
	}

	/** @test */
	function it_flashes_to_the_session()
	{
		$this->session->shouldReceive('has')->andReturn(false);
		$flash = new FlashFactory($this->session);

		$data = ['level' => 'laraveliscool', 'message' => 'Laravel is really cool.'];

		$this->session->shouldReceive('flash')->with($flash->key, [$data]);

		$flash->create($data['level'], $data['message']);
	}

	/** @test */
	function it_creates_one_flash()
	{
		$flash = new FlashFactory($this->session);

		$data = [
			['level' => 'testingmagic', 'message' => 'You won the lottery!']
		];

		$this->session->shouldReceive('flash')->with($flash->key, $data);

		$flash->testingmagic('You won the lottery!');
	}

	/** @test */
	function it_returns_true_when_flash_exists()
	{
		$data = [
			['level' => 'drdre', 'message' => 'Flash messages are cool.'],
			['level' => 'testing', 'message' => 'Hello world!']
		];

		$this->session->shouldReceive('has')->andReturn(true);
		$this->session->shouldReceive('get')->andReturn($data);

		$flash = new FlashFactory($this->session);

		$this->assertTrue($flash->exists());
		$this->assertTrue($flash->has($data[0]['level']));
		$this->assertTrue($flash->has($data[1]['level']));
	}

	/** @test */
	function it_returns_false_when_flash_does_not_exist()
	{
		$flash = new FlashFactory($this->session);

		$this->assertFalse($flash->exists());
		$this->assertFalse($flash->has('drdre'));
	}

	/** @test */
	function it_doesnt_require_a_message()
	{
		$flash = new FlashFactory($this->session);

		$this->session->shouldReceive('flash')->with($flash->key, [['level' => 'danger', 'message' => null]]);

		$flash->danger();
	}

	/** @test */
	function it_returns_a_level_without_a_message()
	{
		$this->session->shouldReceive('has')->andReturn(true);
		$this->session->shouldReceive('get')->with($this->key)->andReturn([
			['level' => 'warning', 'message' => null]
		]);

		$flash = new FlashFactory($this->session);

		$this->assertEquals($flash->level, 'warning');
		$this->assertEquals($flash->message, null);
	}

	/** @test */
	function it_reflashes_to_the_session()
	{
		$data = [
			['level' => 'warning', 'message' => 'This is a warning.'],
			['level' => 'danger', 'message' => 'This is a very dangerous message.']
		];

		$this->session->shouldReceive('has')->andReturn(true);
		$this->session->shouldReceive('keep')->with([$this->key]);
		$this->session->shouldReceive('get')->with($this->key)->andReturn($data);

		$flash = new FlashFactory($this->session);

		$flash->again();
	}

	/** @test */
	function it_reflashes_to_the_session_and_adds_new_flashes()
	{
		$data = [
			['level' => 'warning', 'message' => 'This is a warning.'],
			['level' => 'danger', 'message' => 'This is a very dangerous message.']
		];

		$this->session->shouldReceive('has')->andReturn(true);
		$this->session->shouldReceive('keep')->with([$this->key]);
		$this->session->shouldReceive('get')->with($this->key)->andReturn($data);

		$data[] = ['level' => 'success', 'message' => 'This should be in the array as well.'];
		$this->session->shouldReceive('flash')->with($this->key, $data);

		$flash = new FlashFactory($this->session);

		$flash->again();

		$flash->success('This should be in the array as well.');
	}

	/** @test */
	function it_creates_multiple_flashes()
	{
		$data = [
			['level' => 'warning', 'message' => 'Something bad happened.'],
			['level' => 'warning', 'message' => 'We were not able to complete your request.'],
		];

		$this->session->shouldReceive('flash')->with($this->key, [$data[0]]);
		$this->session->shouldReceive('flash')->with($this->key, $data);

		$flash = new FlashFactory($this->session);

		$flash->warning('Something bad happened.')
			  ->warning('We were not able to complete your request.');

	}

	/** @test */
	function it_loads_multiple_flashes_from_session()
	{
		$data = [
			['level' => 'success', 'message' => 'You have been successfully logged in!'],
			['level' => 'danger', 'message' => 'Your profile is not yet complete!'],
			['level' => 'warning', 'message' => 'There was an error in processing your form.']
		];
		
		$this->session->shouldReceive('has')->andReturn(true);
		$this->session->shouldReceive('get')->with($this->key)->andReturn($data);

		$flash = new FlashFactory($this->session);

		$this->assertEquals($flash->level, 'warning');
		$this->assertEquals($flash->message, 'There was an error in processing your form.');

		foreach ($flash->all() as $key => $item) {
			$this->assertEquals($item->level, $data[$key]['level']);
			$this->assertEquals($item->message, $data[$key]['message']);
		}
	}

}
