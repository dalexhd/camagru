<?php

namespace core;

foreach (glob("../core/helpers/*.php") as $filename) {
	require_once $filename;
}
