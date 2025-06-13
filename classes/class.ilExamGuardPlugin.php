<?php
class ilExamGuardPlugin extends ilUserInterfaceHookPlugin {
    protected $config;

    public function getPluginName() {
        return "ExamGuard";
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

    public function modifyGUI($a_comp, $a_part, $a_par = array()) {
        global $tpl;

        $cmd = $_GET["cmd"] ?? "";
        $refid_list = $this->config->get("refid_list");
        $refid_array = array_filter(array_map("trim", explode(",", $refid_list)));
        $current_ref_id = (int) ($_GET["ref_id"] ?? 0);

        if (
            $cmd === "infoScreen" &&
            $this->config->get("start_delay") === "1" &&
            in_array($current_ref_id, $refid_array)
        ) {
            $tpl->addJavaScript($this->getPreTestJsPath());
        }

        $global_val = $this->config->get("global_block");
        $active_id = (int) ($_GET["active_id"] ?? 0);

        if ($global_val === "1" || ($active_id > 0 && in_array($current_ref_id, $refid_array))) {
            $tpl->addJavaScript($this->getJsPath());
        }
    }

    private function getJsPath() {
        return "./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/ExamGuard/js/examguard.js";
    }

    private function getPreTestJsPath() {
        return "./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/ExamGuard/js/examguard_pretest.js";
    }
}
