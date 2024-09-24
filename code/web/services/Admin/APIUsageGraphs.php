<?php

require_once ROOT_DIR . '/services/Admin/AbstractUsageGraphs.php';
require_once ROOT_DIR . '/sys/SystemLogging/APIUsage.php';
require_once ROOT_DIR . '/sys/Utils/GraphingUtils.php';

class Admin_APIUsageGraphs extends Admin_AbstractUsageGraphs {
	function launch(): void {
		global $interface;

		$stat = $_REQUEST['stat'];
		if (!empty($_REQUEST['instance'])) {
			$instanceName = $_REQUEST['instance'];
		} else {
			$instanceName = '';
		}

		$title = 'Aspen Discovery API Usage Graph';
		$interface->assign('section', 'Admin');
		$interface->assign('showCSVExportButton', true);
		$interface->assign('graphTitle', $title);
		$this->assignGraphSpecificTitle($stat);
		$this->getAndSetInterfaceDataSeries($stat, $instanceName);
		$interface->assign('stat', $stat);
		$interface->assign('propName', 'exportToCSV');
		$title = $interface->getVariable('graphTitle');
		$this->display('usage-graph.tpl', $title);
	}
	function getBreadcrumbs(): array {
		$breadcrumbs = [];
		$breadcrumbs[] = new Breadcrumb('/Admin/Home', 'Administration Home');
		$breadcrumbs[] = new Breadcrumb('/Admin/Home#system_reports', 'System Reports');
		$breadcrumbs[] = new Breadcrumb('/Admin/APIUsageDashboard', 'Usage Dashboard');
		$breadcrumbs[] = new Breadcrumb('', 'Usage Graph');
		return $breadcrumbs;
	}

	function getActiveAdminSection(): string {
		return 'system_reports';
	}

	function canView(): bool {
		return UserAccount::userHasPermission([
			'View Dashboards',
			'View System Reports',
		]);
	}

	protected function getAndSetInterfaceDataSeries($stat, $instanceName): void {
		global $interface;

		$dataSeries = [];
		$columnLabels = [];
		$usage = new APIUsage();
		$usage->groupBy('year, month');
		if (!empty($instanceName)) {
			$usage->instance = $instanceName;
		}
		$usage->whereAdd("method = '$stat'");
		$usage->selectAdd();
		$usage->selectAdd('year');
		$usage->selectAdd('month');
		$usage->orderBy('year, month');
		$dataSeries[$stat] = GraphingUtils::getDataSeriesArray(count($dataSeries));
		$usage->selectAdd('SUM(numCalls) as numCalls');

		//Collect results
		$usage->find();

		while ($usage->fetch()) {
			$curPeriod = "{$usage->month}-{$usage->year}";
			$columnLabels[] = $curPeriod;
			/** @noinspection PhpUndefinedFieldInspection */
			$dataSeries[$stat]['data'][$curPeriod] = $usage->numCalls;
		}
		$interface->assign('columnLabels', $columnLabels);
		$interface->assign('dataSeries', $dataSeries);
		$interface->assign('translateDataSeries', true);
		$interface->assign('translateColumnLabels', false);
	}

	protected function assignGraphSpecificTitle($stat): void {
		global $interface;
		$title = 'Aspen Discovery API Usage Graph';
		$title .= " - $stat";
		$interface->assign('graphTitle', $title);
	}
}
