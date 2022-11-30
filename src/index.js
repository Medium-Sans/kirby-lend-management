import Dashboard from "./components/Dashboard.vue";
import Inventory from "./components/Inventory.vue";
import Category from "./components/Category.vue";
import Borrowers from "./components/Borrowers.vue";
import Loan from "./components/Loan.vue";

panel.plugin("scardoso/lendmanagement", {
  components: {
    "k-dashboard-view": Dashboard,
    "k-inventory-view": Inventory,
    "k-category-view":  Category,
    "k-borrowers-view": Borrowers,
    "k-loan-view":      Loan,
  }
});
