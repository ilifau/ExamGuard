<?php
class ilExamGuardConfigGUI extends ilPluginConfigGUI {
    protected $plugin;

    public function performCommand($cmd) {
        global $ilCtrl, $tpl;
        $this->plugin = $this->getPluginObject();

        switch ($cmd) {
            case "configure":
            case "save":
                $this->$cmd();
                break;
            default:
                $this->configure();
                break;
        }
    }

    protected function configure() {
        global $tpl;
        $form = $this->initForm();
        $tpl->setContent($form->getHTML());
    }

    protected function save() {
        global $ilCtrl, $tpl;
        $form = $this->initForm();
        if ($form->checkInput()) {
            $global_block = $form->getInput("global_block") ? "1" : "";
            $refid_list   = $form->getInput("refid_list") ?? "";
            $start_delay  = $form->getInput("start_delay") ? "1" : "";

            $this->plugin->getConfig()->set("global_block", $global_block);
            $this->plugin->getConfig()->set("refid_list", trim($refid_list));
            $this->plugin->getConfig()->set("start_delay", $start_delay);

            $ilCtrl->redirect($this, "configure");
        } else {
            $form->setValuesByPost();
            $tpl->setContent($form->getHTML());
        }
    }

    protected function initForm() {
        global $ilCtrl;
        $form = new ilPropertyFormGUI();
        $form->setTitle("ExamGuard - Einstellungen");
        $form->setFormAction($ilCtrl->getFormAction($this));

        $saved_global      = $this->plugin->getConfig()->get("global_block");
        $saved_refids      = $this->plugin->getConfig()->get("refid_list");
        $saved_start_delay = $this->plugin->getConfig()->get("start_delay");

        $cb = new ilCheckboxInputGUI("Global aktivieren?", "global_block");
        $cb->setInfo("Wenn aktiviert, wird Copy&Paste in ganz ILIAS blockiert.");
        $cb->setChecked($saved_global === "1");
        $form->addItem($cb);

        $ti = new ilTextInputGUI("ref_ids (Kommagetrennt)", "refid_list");
        $ti->setInfo("Blockiert nur bei diesen ref_ids, falls global nicht aktiv ist (z. B.: 24093,24100).");
        $ti->setValue($saved_refids);
        $form->addItem($ti);

        $cb_delay = new ilCheckboxInputGUI("Startverzögerung aktivieren?", "start_delay");
        $cb_delay->setInfo("Aktiviert einen Countdown von 1–10 Sekunden vor dem Teststart.");
        $cb_delay->setChecked($saved_start_delay === "1");
        $form->addItem($cb_delay);

        $form->addCommandButton("save", "Speichern");
        return $form;
    }
}
