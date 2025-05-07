<?php
class ilExamGuardPlugin extends ilUserInterfaceHookPlugin {
    protected $config;
    public function __construct($db = null, $component_repository = null, $plugin_id = null) {
        if ($db === null || $component_repository === null || $plugin_id === null) {
            parent::__construct();
        } else {
            parent::__construct($db, $component_repository, $plugin_id);
        }
        if (!$this->config) {
            require_once __DIR__ . "/class.ilExamGuardConfig.php";
            $this->config = new ilExamGuardConfig("examguard");
        }
    }
    public function getPluginName() {
        return "ExamGuard";
    }
    public function modifyGUI($a_comp, $a_part, $a_par = array()) {
        global $tpl;
        if (!$tpl) return;
        $global_val = $this->config->get("global_block");
        $refid_list = $this->config->get("refid_list");
        $refid_array = array_filter(array_map("trim", explode(",", $refid_list)));
        $active_id = (int) ($_GET["active_id"] ?? 0);
        $current_ref_id = (int) ($_GET["ref_id"] ?? 0);
        if ($global_val === "1" || ($active_id > 0 && in_array($current_ref_id, $refid_array))) {
            $tpl->addJavaScript($this->getJsPath());
        }
    }
    private function getJsPath() {
        return "./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/"
             . $this->getPluginName() . "/js/examguard.js";
    }
    public function hasConfiguration() {
        return true;
    }
    public function getConfig() {
        if (!$this->config) {
            require_once __DIR__ . "/class.ilExamGuardConfig.php";
            $this->config = new ilExamGuardConfig("examguard");
        }
        return $this->config;
    }
}
