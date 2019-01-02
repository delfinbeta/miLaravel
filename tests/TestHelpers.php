<?php

namespace Tests;

trait TestHelpers
{
	protected function getValidData(array $custom = []) {
    return array_merge($this->defaultData(), $custom);
  }

  protected function defaultData() {
  	return $this->defaultData;
  }
}