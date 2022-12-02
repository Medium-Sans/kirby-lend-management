import Dashboard from "./components/Dashboard.vue";
import Inventory from "./components/Inventory.vue";
import Category from "./components/Category.vue";
import Item from "./components/Item.vue";
import Borrowers from "./components/Borrowers.vue";
import Loan from "./components/Loan.vue";
import LoanAdd from "./components/LoanAdd.vue";

panel.plugin("scardoso/lendmanagement", {
  components: {
    "k-dashboard-view": Dashboard,
    "k-inventory-view": Inventory,
    "k-borrowers-view": Borrowers,
    "k-category-view":  Category,
    "k-item-view":      Item,
    "k-loan-view":      Loan,
    "k-loan-add-view":  LoanAdd,
  }
});
