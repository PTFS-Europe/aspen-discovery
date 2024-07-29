<?php

require_once ROOT_DIR . '/services/Admin/Admin.php';
require_once ROOT_DIR . '/sys/SystemLogging/AspenUsage.php';
require_once ROOT_DIR . '/sys/Summon/UserSummonUsage.php';
require_once ROOT_DIR . '/sys/Summon/SummonRecordUsage.php';

class Summon_UsageGraphs extends Admin_Admin {

	function launch() {
		global $interface;

		$title = 'Summon Usage Graph';
		$stat = $_REQUEST['stat'];
		if (!empty($_REQUEST['instance'])) {
			$instanceName = $_REQUEST['instance'];
		} else {
			$instanceName = '';
		}

		$dataSeries = [];
		$columnLabels = [];

		switch ($stat) {
			case 'activeUsers':
				$title .= ' - Active Users';
			break;
			case 'numRecordsViewed':
				$title .= ' - Number of Records Viewed';
			break;
			case 'numRecordsClicked':
				$title .= ' - Number of Records Clicked';
			break;
			case 'totalClicks':
				$title .= ' - Total Clicks';
			break;
		}
		if (
			$stat == 'numRecordsViewed' ||
			$stat == 'numRecordsClicked' ||
			$stat == 'totalClicks'
			) {
				$summonRecordUsage = new SummonRecordUsage();
				$summonRecordUsage->groupBy('year, month');
				if (!empty($instanceName)) {
					$summonRecordUsage->instance = $instanceName;
				}
				$summonRecordUsage->selectAdd();
				$summonRecordUsage->selectAdd('year');
				$summonRecordUsage->selectAdd('month');
				$summonRecordUsage->orderBy('year, month');
				if ($stat == 'numRecordsViewed') {
					$dataSeries['Number of Records Viewed'] = [
						'borderColor' => 'rgba(255, 99, 132, 1)',
						'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
						'data' => [],
					];
					$summonRecordUsage ->selectAdd('SUM(IF(timesViewedInSearch>0,1,0)) as numRecordsViewed');
				}
				if ($stat == 'numRecordsClicked') {
					$dataSeries['Number of Records Clicked'] = [
						'borderColor' => 'rgba(255, 99, 132, 1)',
						'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
						'data' => [],
					];
					$summonRecordUsage ->selectAdd('SUM(IF(timesUsed>0,1,0)) as numRecordsUsed');
				}
				if ($stat == 'totalClicks') {
					$dataSeries['Total Clicks'] = [
						'borderColor' => 'rgba(255, 99, 132, 1)',
						'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
						'data' => [],
					];
					$summonRecordUsage ->selectAdd('SUM(timesUsed) as numClicks');
				}

				// Collect results
				$summonRecordUsage->find();
				while ($summonRecordUsage->fetch()) {
					$curPeriod = "{$summonRecordUsage->month}-{$summonRecordUsage->year}";
					$columnLabels[] = $curPeriod;
					if ($stat == 'numRecordsViewed') {
						/** @noinspection PhpUndefinedFieldInspection */
						$dataSeries['Number of Records Viewed']['data'][$curPeriod] = $summonRecordUsage->numRecordsViewed;
					}
					if ($stat == 'numRecordsClicked') {
						/** @noinspection PhpUndefinedFieldInspection */
						$dataSeries['Number of Records Clicked']['data'][$curPeriod] = $summonRecordUsage->numRecordsUsed;
					}
					if ($stat == 'totalClicks') {
						/** @noinspection PhpUndefinedFieldInspection */
						$dataSeries['Total Clicks']['data'][$curPeriod] = $summonRecordUsage->numClicks;
					}

				}
			}
		$interface->assign('columnLabels', $columnLabels);
		$interface->assign('dataSeries', $dataSeries);
		$interface->assign('translateDataSeries', true);
		$interface->assign('translateColumnLabels', false);

		$interface->assign('graphTitle', $title);
		$this->display('usage-graph.tpl', $title);
	}

	function getActiveAdminSection(): string {
		return 'summon';
	}

	function canView(): bool {
		return UserAccount::userHasPermission([
			'View Dashboards',
			'View System Reports',
		]);
	}

	function getBreadcrumbs(): array {
		$breadcrumbs = [];
		$breadcrumbs[] = new Breadcrumb('/Admin/Home', 'Administration Home');
		$breadcrumbs[] = new Breadcrumb('/Admin/Home#summon', 'Summon');
		$breadcrumbs[] = new Breadcrumb('/Summon/SummonDashboard', 'Summon Usage Dashboard');
		$breadcrumbs[] = new Breadcrumb('', 'Usage Graph');
		return $breadcrumbs;
	}
}