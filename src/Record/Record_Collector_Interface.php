<?php

namespace Rarst\Laps\Record;

interface Record_Collector_Interface {

	/**
	 * @return Record_Interface[]
	 */
	public function get_records();
}
