<template>
  <k-inside>
    <k-view>

      <k-header>
        {{ borrower.firstname }} {{ borrower.lastname }} - {{ loan.startDate }}

        <k-button-group slot="left">
          <k-button icon="add"
                    :text="$t('lendmanagement.loan.add')"
                    @click="$dialog('lendmanagement/loan/create')" />
          <k-button icon="undo"
                    :text="$t('lendmanagement.loan.notify')"
                    @click="$dialog('lendmanagement/loan/notifyexpired')" />
        </k-button-group>
      </k-header>

      <k-grid gutter="large">

        <k-column width="1/2">

          <k-form v-model="loan" @input="input" @submit="submit" :fields="{
              startDate: {
                label: 'Début du prêt',
                type: 'date',
                time: false,
                required: true,
                width: '1/2'
              },
              endDate: {
                label: 'Fin du prêt',
                type: 'date',
                time: false,
                required: true,
                width: '1/2'
              },
              line: {
                type: 'line'
              }
            }" />

          <k-form v-model="borrower" @input="input" @submit="submit" :fields="{
              firstname: {
                label: $t('lendmanagement.borrowers.table.firstname'),
                type: 'text',
                required: true,
                width: '1/2'
              },
              lastname: {
                label: $t('lendmanagement.borrowers.table.lastname'),
                type: 'text',
                required: true,
                width: '1/2'
              },
              email: {
                label: $t('lendmanagement.borrowers.table.email'),
                required: true,
                type: 'text'
              },
              phone: {
                label: $t('lendmanagement.borrowers.table.phone'),
                type: 'text'
              },
            }" />
        </k-column>

        <k-column width="1/2">
          <table class="k-products">
            <thead>
              <tr>
                <th colspan="3">{{ $t('lendmanagement.items') }}</th>
              </tr>
            </thead>
            <tr>
              <th>{{ $t('lendmanagement.loan.table.name') }}</th>
              <th>{{ $t('lendmanagement.loan.table.quantity') }}</th>
              <th></th>
            </tr>
            <tr v-for="(item, id) in items" :key="id">
              <td>{{ item.title }}</td>
              <td>{{ item.quantity }}</td>
              <td class="k-product-options">
                <k-options-dropdown :options="'products/' + id" />
              </td>
            </tr>
          </table>
        </k-column>

      </k-grid>
    </k-view>
  </k-inside>
</template>

<script>
export default {
  props: {
    items: Array,
    borrower: Object,
    loan: Object
  },
};
</script>

<style>
.k-headline {
  margin-bottom: 8px;
}
</style>
