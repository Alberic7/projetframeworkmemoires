import Alpine from "alpinejs";
import registerUi from "./ui";

window.Alpine = Alpine;

// Dropdown simple handler pour la sidebar et l'en-tête
Alpine.data("dropdown", () => ({
    open: false,
    toggle() {
        this.open = !this.open;
    },
    close() {
        this.open = false;
    }
}));

// Enregistrer les composants UI de TailAdmin
registerUi(Alpine);

Alpine.start();
