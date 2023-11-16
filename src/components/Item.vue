<template>
  <k-inside>
    <k-header>
      {{ item.name }}

      <k-button-group slot="buttons">
        <k-button
          text="Delete"
          icon="trash"
          variant="filled"
          @click="$dialog('lendmanagement/inventory/item/' + item.id + '/delete')"
        />
        <k-button
          text="Print"
          icon="print"
          variant="filled"
          @click="print"
        />
        <k-button
          icon="add"
          variant="filled"
          @click="$dialog('lendmanagement/inventory/category/create')"
        >
          {{ $t('lendmanagement.category.add') }}
        </k-button>
      </k-button-group>
    </k-header>

    <k-grid style="gap: 3rem;">
      <k-column width="2/3">

        <k-form v-model="item" @input="input" @submit="submit" :fields="{
            name: {
              label: $t('lendmanagement.item.form.name'),
              type: 'text',
              required: true,
              width: '1'
            },
            description: {
              label: $t('lendmanagement.item.form.description'),
              type: 'textarea',
              required: false,
              width: '1'
            },
            categoryId: {
              label: $t('lendmanagement.category'),
              type: 'multiselect',
              required: true,
              search: true,
              options: categories,
              max: 1,
              width: '1/2'
            },
            quantity: {
              label: $t('lendmanagement.item.form.quantity'),
              type: 'number',
              required: true,
              width: '1/2'
            },
            notes: {
              label: $t('lendmanagement.item.form.note'),
              type: 'textarea',
              required: false,
              width: '1',
              size: 'medium'
            },
          }"/>

        <k-button variant="filled" icon="check" @click="submit">{{ $t('lendmanagement.lendAdd.save') }}</k-button>
      </k-column>

      <k-column width="1/3">
        <k-field label="Qr-Code">
          <k-image class="k-image"
                   :src="item.qr_code"
                   ratio="1/1"/>
        </k-field>
      </k-column>
    </k-grid>
  </k-inside>
</template>

<script>
export default {
  props: {
    item: Object,
    categories: Array
  },
  methods: {
    forceFileDownload(response, title) {
      const url = window.URL.createObjectURL(new Blob([response], {
        encoding: "UTF-8",
        type: "text/plain;charset=UTF-8"
      }))
      const link = document.createElement('a')
      link.href = url
      link.setAttribute('download', title)
      document.body.appendChild(link)
      link.click()
    },
    submit() {
      this.$api.post('lendmanagement/item/' + this.item.id + '/update', this.item);
    },
    async print() {
      const label = await this.$api.post('lendmanagement/item/' + this.item.id + '/print');
      this.forceFileDownload(atob(label.data), this.item.id + '-item.label');
    }
  }
};
</script>

<style>
.k-form {
  margin-bottom: var(--spacing-4);
}

.k-image {
  height: 100%;
  width: auto;
}
</style>
