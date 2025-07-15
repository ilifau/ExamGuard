<?php

class ilExamGuardPlugin extends ilUserInterfaceHookPlugin
{
    protected ?ilExamGuardConfig $config = null;
    const CONFIG_KEY_ENABLE_CALCULATOR = 'enable_calculator';


    public function getPluginName(): string
    {
        return "ExamGuard";
    }

    public function hasConfiguration(): bool
    {
        return true;
    }

    public function getConfig(): ilExamGuardConfig
    {
        if (!$this->config) {
            require_once __DIR__ . "/class.ilExamGuardConfig.php";
            $this->config = new ilExamGuardConfig("examguard");
        }
        return $this->config;
    }

    public function modifyGUI(string $a_comp, string $a_part, array $a_par = []): void
    {
        $DIC = $GLOBALS['DIC'] ?? null;
        if (!$DIC) {
            return;
        }

        // Verfügbarkeitsprüfung für Template (headless contexts vermeiden)
        if (!$DIC->offsetExists('tpl') || !$DIC['tpl'] instanceof ilGlobalTemplateInterface) {
            return;
        }

        $tpl = $DIC['tpl'];
        $cmd = $_GET["cmd"] ?? "";
        $refid_list = $this->getConfig()->get("refid_list");
        $refid_array = array_filter(array_map("trim", explode(",", $refid_list)));
        $current_ref_id = (int) ($_GET["ref_id"] ?? 0);

        // Startverzögerung aktivieren
        if (
            $cmd === "infoScreen" &&
            $this->getConfig()->get("start_delay") === "1" &&
            in_array($current_ref_id, $refid_array)
        ) {
            $tpl->addJavaScript($this->getPreTestJsPath());
        }

        // Globaler oder selektiver ExamGuard-Block aktivieren
        $global_val = $this->getConfig()->get("global_block");
        $active_id = (int) ($_GET["active_id"] ?? 0);

        if ($global_val === "1" || ($active_id > 0 && in_array($current_ref_id, $refid_array))) {
            $tpl->addJavaScript($this->getJsPath());
        }
    }

    private function getJsPath(): string
    {
        return "./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/ExamGuard/js/examguard.js";
    }

    private function getPreTestJsPath(): string
    {
        return "./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/ExamGuard/js/examguard_pretest.js";
    }
}
