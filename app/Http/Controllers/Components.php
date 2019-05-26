<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Nahid\JsonQ\Jsonq;
use Illuminate\Support\Collection;


class Components {
    public static function get() {
        return [
            "Projects",
            "Drawing",
            "Alchemical",
            "WPF",
            "Product Generation",
            "Fax Server",
            "Math Expressions",
            "Infrastucture",
            "Linux Client",
            "Application Context",
            "Schema Builder",
            "Reports",
            "QA",
            "Printing",
            "Financial",
            "Timesheets",
            "Administration",
            "DevExpress",
            "Overall System",
            "Navigation",
            "Controller Unit",
            "Emailing",
            "Windows Forms",
            "Messenger Client",
            "Invoice Management",
            "Logging",
            "Structure formulas",
            "Server",
            "Users Management",
            "UI Components",
            "Litigation",
            "Calculations",
            "Documents",
            "IO",
            "Third Party",
            "Web Client",
            "Performance",
            "Monitoring",
            "Software",
            "Windows Client",
            "Spell",
            "Hardware",
            "Export to XML",
            "Outputs",
            "Validation"
        ];
    }
}