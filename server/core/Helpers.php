<?php

namespace core;


// Well, we load all helpers here. And this basically instantiates them.
foreach (glob("../core/helpers/*.php") as $filename) {
	require_once $filename;
}
