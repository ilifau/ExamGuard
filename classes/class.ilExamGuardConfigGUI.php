<?php
/**
 * @ilCtrl_isCalledBy ilExamGuardConfigGUI: ilObjComponentSettingsGUI
 */

class ilExamGuardConfigGUI extends ilPluginConfigGUI
{
    protected ilExamGuardPlugin $plugin;

    public function performCommand(string $cmd): void
    {
        $this->plugin = $this->getPluginObject();
        global $DIC;
        $ctrl = $DIC->ctrl();
        $tpl = $DIC->ui()->mainTemplate();

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

    protected function configure(): void
    {
        global $DIC;
        $tpl = $DIC->ui()->mainTemplate();
        $form = $this->initForm();
        $tpl->setContent($form->getHTML());
    }

    protected function save(): void
    {
        global $DIC;
        $ctrl = $DIC->ctrl();
        $tpl = $DIC->ui()->mainTemplate();

        $form = $this->initForm();
        if ($form->checkInput()) {
            $this->plugin->getConfig()->set("global_block", $form->getInput("global_block") ? "1" : "");
            $this->plugin->getConfig()->set("refid_list", trim($form->getInput("refid_list") ?? ""));
            $this->plugin->getConfig()->set("start_delay", $form->getInput("start_delay") ? "1" : "");
            $ctrl->redirect($this, "configure");
        } else {
            $form->setValuesByPost();
            $tpl->setContent($form->getHTML());
        }
    }

    protected function initForm(): ilPropertyFormGUI
    {
        global $DIC;
        $ctrl = $DIC->ctrl();
        $form = new ilPropertyFormGUI();
        $form->setTitle("ExamGuard - Einstellungen");
        $form->setFormAction($ctrl->getFormAction($this));

        $saved_global = $this->plugin->getConfig()->get("global_block");
        $saved_refids = $this->plugin->getConfig()->get("refid_list");
        $saved_delay  = $this->plugin->getConfig()->get("start_delay");

        $cb = new ilCheckboxInputGUI("Global aktivieren?", "global_block");
        $cb->setInfo("Wenn aktiviert, wird Copy&Paste in ganz ILIAS blockiert.");
        $cb->setChecked($saved_global === "1");
        $form->addItem($cb);

        $ti = new ilTextInputGUI("ref_ids (Kommagetrennt)", "refid_list");
        $ti->setInfo("Blockiert nur bei diesen ref_ids, falls global nicht aktiv ist (z. B.: 24093,24100).");
        $ti->setValue($saved_refids);
        $form->addItem($ti);

        $cb_delay = new ilCheckboxInputGUI("Startverzögerung aktivieren? - Noch ohne funktion", "start_delay");
        $cb_delay->setInfo("Aktiviert einen Countdown von 1–10 Sekunden vor dem Teststart.");
        $cb_delay->setChecked($saved_delay === "1");
        $form->addItem($cb_delay);

        $form->addCommandButton("save", "Speichern");
        return $form;
    }
}
