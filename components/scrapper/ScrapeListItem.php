<?php
namespace app\components\scrapper;

abstract class ScrapeListItem
{
	public abstract function getUrl();
	public abstract function getItem();
}