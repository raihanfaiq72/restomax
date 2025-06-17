<script setup lang="ts">
    // import 3rd party
    import { ref, h, computed, watch } from 'vue';
    import type { ColumnDef, ColumnFiltersState, SortingState, VisibilityState } from '@tanstack/vue-table';
    import { FlexRender, getCoreRowModel, getFilteredRowModel, getPaginationRowModel, getSortedRowModel, useVueTable } from '@tanstack/vue-table';
    import { ArrowUpDown, ChevronDown, MoreHorizontal, PlusCircle, Pencil, Trash2 } from 'lucide-vue-next';
    import { vAutoAnimate } from '@formkit/auto-animate/vue';
    import { useForm, usePage, router } from '@inertiajs/vue3';

    // import components shadcn ui
    import { Button } from '@/components/ui/button';
    import { Checkbox } from '@/components/ui/checkbox';
    import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuTrigger, DropdownMenuCheckboxItem } from '@/components/ui/dropdown-menu';
    import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
    import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle } from '@/components/ui/alert-dialog';
    import { Input } from '@/components/ui/input';
    import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
    import { Label } from '@/components/ui/label';
    import { toast } from 'vue-sonner';

    // Tipe data (export agar bisa di-import oleh parent)
    export interface Category {
        id: number;
        name: string;
        slug: string;
        created_at: string;
        updated_at: string;
    }

    // Terima props dan berikan nilai default array kosong agar tidak error
    const props = withDefaults(defineProps<{categories: Category[]}>(), {
        categories: () => [],
    });

    // State Management untuk Aksi UI
    const isDialogOpen = ref(false);
    const isAlertOpen = ref(false);
    const editingCategory = ref<Category | null>(null);
    const categoryToDelete = ref<Category | null>(null);
    const dialogTitle = computed(() => editingCategory.value ? 'Edit Kategori' : 'Tambah Kategori Baru');
    
    // Form Handling dengan Inertia.js
    const form = useForm({ name: '' });

    // Logika Handler Aksi
    const openDialog = (category: Category | null) => { editingCategory.value = category; if (category) { form.name = category.name; } else { form.reset(); } form.clearErrors(); isDialogOpen.value = true; };
    const openDeleteAlert = (category: Category) => { categoryToDelete.value = category; isAlertOpen.value = true; };
    const onSubmit = () => { if (editingCategory.value) { form.put(route('categories.update', editingCategory.value.slug), { preserveScroll: true, onSuccess: () => { isDialogOpen.value = false; toast.success(`Kategori "${form.name}" berhasil diperbarui.`); form.reset(); }, }); } else { form.post(route('categories.store'), { preserveScroll: true, onSuccess: () => { isDialogOpen.value = false; toast.success(`Kategori "${form.name}" berhasil ditambahkan.`); form.reset(); }, }); } };
    const confirmDelete = () => { if (categoryToDelete.value) { const deletedCategoryName = categoryToDelete.value.name; router.delete(route('categories.destroy', categoryToDelete.value.slug), { preserveScroll: true, onSuccess: () => { categoryToDelete.value = null; toast.success(`Kategori "${deletedCategoryName}" telah dihapus.`); }, }); } isAlertOpen.value = false; };
    
    // Konfigurasi Kolom Data Table
    const columns: ColumnDef<Category>[] = [
        { id: 'select', header: ({ table }) => h(Checkbox, { checked: table.getIsAllPageRowsSelected() || (table.getIsSomePageRowsSelected() && 'indeterminate'), 'onUpdate:checked': (value: boolean) => table.toggleAllPageRowsSelected(!!value), 'aria-label': 'Select all' }), cell: ({ row }) => h(Checkbox, { checked: row.getIsSelected(), 'onUpdate:checked': (value: boolean) => row.toggleSelected(!!value), 'aria-label': 'Select row' }), enableSorting: false, enableHiding: false },
        { accessorKey: 'name', header: ({ column }) => h(Button, { variant: 'ghost', onClick: () => column.toggleSorting(column.getIsSorted() === 'asc') }, () => ['Nama Kategori', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })]), cell: ({ row }) => h('div', { class: 'font-medium' }, row.getValue('name')) },
        { accessorKey: 'created_at', header: 'Tanggal Dibuat', cell: ({ row }) => h('div', {}, new Date(row.getValue('created_at')).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' })) },
        
        // ==================================================================
        // === PERBAIKAN UTAMA ADA DI BAGIAN INI (KOLOM ACTIONS) ===
        // ==================================================================
        { 
            id: 'actions', 
            enableHiding: false, 
            cell: ({ row }) => {
                const category = row.original;
                return h('div', { class: 'relative text-right' }, h(DropdownMenu, {}, {
                    // Anak-anak komponen dibungkus dalam slot default berbentuk fungsi
                    default: () => [
                        h(DropdownMenuTrigger, { asChild: true },
                            h(Button, { variant: 'ghost', class: 'h-8 w-8 p-0' }, () => [
                                h('span', { class: 'sr-only' }, 'Buka menu'),
                                h(MoreHorizontal, { class: 'h-4 w-4' }),
                            ])
                        ),
                        h(DropdownMenuContent, { align: 'end' }, [
                            h(DropdownMenuLabel, {}, 'Aksi'),
                            h(DropdownMenuItem, {
                                class: "flex items-center gap-2 cursor-pointer",
                                onClick: () => openDialog(category)
                            }, () => [h(Pencil, { class: 'h-4 w-4' }), 'Edit']),
                            h(DropdownMenuItem, {
                                class: "flex items-center gap-2 text-red-600 focus:text-red-500 cursor-pointer",
                                onClick: () => openDeleteAlert(category)
                            }, () => [h(Trash2, { class: 'h-4 w-4' }), 'Hapus']),
                        ]),
                    ]
                }));
            }
        },
    ];

    // Konfigurasi Instansi Tabel
    const sorting = ref<SortingState>([]);
    const columnFilters = ref<ColumnFiltersState>([]);
    const columnVisibility = ref<VisibilityState>({});
    const rowSelection = ref({});
    const table = useVueTable({
        get data() { return props.categories },
        columns,
        getCoreRowModel: getCoreRowModel(),
        getPaginationRowModel: getPaginationRowModel(),
        getSortedRowModel: getSortedRowModel(),
        getFilteredRowModel: getFilteredRowModel(),
        onSortingChange: updater => (sorting.value = typeof updater === 'function' ? updater(sorting.value) : updater),
        onColumnFiltersChange: updater => (columnFilters.value = typeof updater === 'function' ? updater(columnFilters.value) : updater),
        onColumnVisibilityChange: updater => (columnVisibility.value = typeof updater === 'function' ? updater(columnVisibility.value) : updater),
        onRowSelectionChange: updater => (rowSelection.value = typeof updater === 'function' ? updater(rowSelection.value) : updater),
        state: {
            get sorting() { return sorting.value },
            get columnFilters() { return columnFilters.value },
            get columnVisibility() { return columnVisibility.value },
            get rowSelection() { return rowSelection.value },
        },
    });
</script>

<template>
    <div class="w-full">
         <div class="flex items-center justify-between py-4">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Manajemen Kategori</h2>
                <p class="text-muted-foreground">Kelola daftar kategori yang tersedia di sini.</p>
            </div>
            <Button @click="openDialog(null)" class="flex items-center gap-2">
                <PlusCircle class="h-4 w-4" /> Tambah Kategori
            </Button>
        </div>

        <div class="flex items-center justify-between py-4">
            <Input class="max-w-sm" placeholder="Filter berdasarkan nama..." :model-value="(table.getColumn('name')?.getFilterValue() as string) ?? ''" @update:model-value="table.getColumn('name')?.setFilterValue($event)" />
            <DropdownMenu>
                <DropdownMenuTrigger as-child>
                    <Button variant="outline" class="ml-auto"> Kolom <ChevronDown class="ml-2 h-4 w-4" /></Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end">
                    <DropdownMenuCheckboxItem v-for="column in table.getAllColumns().filter((column) => column.getCanHide())" :key="column.id" class="capitalize" :checked="column.getIsVisible()" @update:checked="(value: boolean) => column.toggleVisibility(!!value)">
                        {{ column.id === 'created_at' ? 'Tanggal Dibuat' : column.id }}
                    </DropdownMenuCheckboxItem>
                </DropdownMenuContent>
            </DropdownMenu>
        </div>

        <div class="rounded-md border">
            <Table>
                <TableHeader>
                    <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
                        <TableHead v-for="header in headerGroup.headers" :key="header.id">
                            <FlexRender v-if="!header.isPlaceholder" :render="header.column.columnDef.header" :props="header.getContext()" />
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <template v-if="table.getRowModel().rows?.length">
                        <TableRow v-for="row in table.getRowModel().rows" :key="row.id" :data-state="row.getIsSelected() && 'selected'">
                            <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                                <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
                            </TableCell>
                        </TableRow>
                    </template>
                    <TableRow v-else>
                        <TableCell :colspan="columns.length" class="h-24 text-center">Tidak ada data.</TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <div class="flex items-center justify-end space-x-2 py-4">
            <div class="flex-1 text-sm text-muted-foreground">
                {{ table.getFilteredSelectedRowModel().rows.length }} dari {{ table.getFilteredRowModel().rows.length }} baris terpilih.
            </div>
            <div class="space-x-2">
                <Button variant="outline" size="sm" :disabled="!table.getCanPreviousPage()" @click="table.previousPage()">Sebelumnya</Button>
                <Button variant="outline" size="sm" :disabled="!table.getCanNextPage()" @click="table.nextPage()">Selanjutnya</Button>
            </div>
        </div>

        <Dialog :open="isDialogOpen" @update:open="isDialogOpen = $event">
            <DialogContent class="sm:max-w-[425px]" @close-auto-focus="(e) => e.preventDefault()">
                <DialogHeader>
                    <DialogTitle>{{ dialogTitle }}</DialogTitle>
                    <DialogDescription>Isi nama kategori di bawah ini. Klik simpan jika sudah selesai.</DialogDescription>
                </DialogHeader>
                <form @submit.prevent="onSubmit">
                    <div class="grid gap-4 py-4">
                        <div class="grid w-full items-center gap-1.5">
                            <Label for="name">Nama Kategori</Label>
                            <Input id="name" type="text" placeholder="Contoh: Fashion Wanita" v-model="form.name" />
                            <p v-if="form.errors.name" class="text-sm text-red-600">{{ form.errors.name }}</p>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button type="submit" :disabled="form.processing">Simpan</Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
        
        <AlertDialog :open="isAlertOpen" @update:open="isAlertOpen = $event">
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>Apakah Anda Yakin?</AlertDialogTitle>
                    <AlertDialogDescription>
                        Tindakan ini tidak dapat dibatalkan. Ini akan menghapus kategori <span class="font-bold">{{ categoryToDelete?.name }}</span> secara permanen.
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel>Batal</AlertDialogCancel>
                    <AlertDialogAction @click="confirmDelete">Lanjutkan</AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </div>
</template>