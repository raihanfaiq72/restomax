<script setup lang="ts">
    import { ref, h, computed } from 'vue';
    import type { ColumnDef } from '@tanstack/vue-table';
    import { FlexRender, getCoreRowModel, getFilteredRowModel, getPaginationRowModel, getSortedRowModel, useVueTable } from '@tanstack/vue-table';
    import { ArrowUpDown, MoreHorizontal, PlusCircle, Pencil, Trash2, TriangleAlert } from 'lucide-vue-next';
    import { useForm, router } from '@inertiajs/vue3';
    import { Button } from '@/components/ui/button';
    import { Checkbox } from '@/components/ui/checkbox';
    import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
    import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
    import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle } from '@/components/ui/alert-dialog';
    import { Input } from '@/components/ui/input';
    import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
    import { Label } from '@/components/ui/label';
    import { Textarea } from '@/components/ui/textarea';
    import { toast } from 'vue-sonner';

    export interface Ingredient {
        id: number;
        name: string;
        slug: string;
        stock_quantity: number;
        unit: string;
        low_stock_threshold: number;
        created_at: string;
        updated_at: string;
    }

    const props = withDefaults(defineProps<{
        ingredients: Ingredient[];
    }>(),{
        ingredients: () => [],
    });

    const isDialogOpen = ref(false);
    const isAlertOpen = ref(false);
    const editingIngredient = ref<Ingredient | null>(null);
    const ingredientToDelete = ref<Ingredient | null>(null);
    const dialogTitle = computed(() => editingIngredient.value ? 'Edit Bahan Baku' : 'Tambah Bahan Baku Baru');

    const form = useForm({
        name: '',
        unit: '',
        stock_quantity: 0,
        low_stock_threshold: 0,
    });

    const openDialog = (ingredient: Ingredient | null) => {
        editingIngredient.value = ingredient;
        if (ingredient) {
            form.name = ingredient.name;
            form.unit = ingredient.unit;
            form.stock_quantity = ingredient.stock_quantity;
            form.low_stock_threshold = ingredient.low_stock_threshold;
        } else {
            form.reset();
        }
        form.clearErrors();
        isDialogOpen.value = true;
    }

    const openDeleteAlert = (ingredient: Ingredient) => {
        ingredientToDelete.value = ingredient;
        isAlertOpen.value = true;
    }

    const onSubmit = () => {
        if (editingIngredient.value) {
            form.put(route('ingredients.update', editingIngredient.value.slug), {
                preserveScroll: true,
                onSuccess: () => {
                    isDialogOpen.value = false;
                    toast.success(`Bahan baku "${form.name}" berhasil diperbarui.`);
                    form.reset();
                }
            });
        } else {
            form.post(route('ingredients.store'), {
                preserveScroll: true,
                onSuccess: () => {
                    isDialogOpen.value = false;
                    toast.success(`Bahan baku "${form.name}" berhasil ditambahkan.`);
                    form.reset();
                }
            });
        }
    }

    const confirmDelete = () => {
        if (ingredientToDelete.value) {
            router.delete(route('ingredients.destroy', ingredientToDelete.value.slug), {
                preserveScroll: true,
                onSuccess: () => {
                    toast.error(`Bahan baku "${ingredientToDelete.value?.name}" telah dihapus.`);
                    ingredientToDelete.value = null
                },
            });
        }
        isAlertOpen.value = false;
    };

    const columns: ColumnDef<Ingredient>[] = [
        { id: 'select', header: ({ table }) => h(Checkbox, { checked: table.getIsAllPageRowsSelected(), 'onUpdate:checked': (value: boolean) => table.toggleAllPageRowsSelected(!!value) }), cell: ({ row }) => h(Checkbox, { checked: row.getIsSelected(), 'onUpdate:checked': (value: boolean) => row.toggleSelected(!!value) }) },
        { accessorKey: 'name', header: ({ column }) => h(Button, { variant: 'ghost', onClick: () => column.toggleSorting(column.getIsSorted() === 'asc') }, () => ['Nama Bahan', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })]) },
        { accessorKey: 'stock_quantity', header: 'Jumlah Stok',
            cell: ({ row }) => {
                const ingredient = row.original;
                const isLowStock = ingredient.stock_quantity <= ingredient.low_stock_threshold;
                const stockText = `${ingredient.stock_quantity} ${ingredient.unit}`;
                
                if (isLowStock) {
                    return h('div', { class: 'flex items-center text-red-500' }, [
                        h(TriangleAlert, { class: 'mr-2 h-4 w-4' }),
                        stockText
                    ]);
                }
                return h('div', {}, stockText);
            }
        },
        { accessorKey: 'low_stock_threshold', header: 'Ambang Batas Stok Rendah' },
        { id: 'actions', cell: ({ row }) => {
            const ingredient = row.original;
            return h('div', { class: 'relative text-right' }, h(DropdownMenu, {}, { default: () => [
                h(DropdownMenuTrigger, { asChild: true }, h(Button, { variant: 'ghost', class: 'h-8 w-8 p-0' }, () => h(MoreHorizontal, { class: 'h-4 w-4' }))),
                h(DropdownMenuContent, { align: 'end' }, [
                    h(DropdownMenuLabel, {}, 'Aksi'),
                    h(DropdownMenuItem, { class: 'flex items-center cursor-pointer', onClick: () => openDialog(ingredient) }, () => [h(Pencil, { class: 'mr-2 h-4 w-4' }), 'Edit']),
                    h(DropdownMenuItem, { class: 'flex items-center text-red-600 cursor-pointer focus:text-red-500', onClick: () => openDeleteAlert(ingredient) }, () => [h(Trash2, { class: 'mr-2 h-4 w-4' }), 'Hapus']),
                ]),
            ]}));
        }},
    ];

    const table = useVueTable({
        get data() { return props.ingredients },
        columns,
        getCoreRowModel: getCoreRowModel(),
        getPaginationRowModel: getPaginationRowModel(),
        getSortedRowModel: getSortedRowModel(),
        getFilteredRowModel: getFilteredRowModel(),
    });
</script>

<template>
    <div class="w-full">
        <div class="flex items-center justify-between py-4">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Manajemen Bahan Baku</h2>
                <p class="text-muted-foreground">Kelola stok bahan baku atau material di sini.</p>
            </div>
            <Button @click="openDialog(null)" class="flex items-center gap-2">
                <PlusCircle class="h-4 w-4" /> Tambah Bahan Baku
            </Button>
        </div>

        <div class="flex items-center justify-between py-4">
            <Input class="max-w-sm" placeholder="Filter berdasarkan nama bahan..." :model-value="(table.getColumn('name')?.getFilterValue() as string) ?? ''" @update:model-value="table.getColumn('name')?.setFilterValue($event)" />
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
                        <TableCell :colspan="columns.length" class="h-24 text-center">
                            Tidak ada data bahan baku.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <div class="flex items-center justify-end space-x-2 py-4">
             <Button variant="outline" size="sm" :disabled="!table.getCanPreviousPage()" @click="table.previousPage()">Sebelumnya</Button>
             <Button variant="outline" size="sm" :disabled="!table.getCanNextPage()" @click="table.nextPage()">Selanjutnya</Button>
        </div>

        <Dialog :open="isDialogOpen" @update:open="isDialogOpen = $event">
            <DialogContent class="sm:max-w-lg">
                <DialogHeader>
                    <DialogTitle>{{ dialogTitle }}</DialogTitle>
                </DialogHeader>
                <form @submit.prevent="onSubmit">
                    <div class="grid gap-y-6 gap-x-4 py-4">
                        <div class="col-span-2">
                            <Label for="name" class="mb-2 inline-block">Nama Bahan Baku</Label>
                            <Input id="name" v-model="form.name" placeholder="Contoh: Tepung Terigu"/>
                            <p v-if="form.errors.name" class="text-sm text-red-600 mt-1">{{ form.errors.name }}</p>
                        </div>
                        <div>
                            <Label for="stock_quantity" class="mb-2 inline-block">Jumlah Stok Saat Ini</Label>
                            <Input id="stock_quantity" type="number" v-model="form.stock_quantity" />
                            <p v-if="form.errors.stock_quantity" class="text-sm text-red-600 mt-1">{{ form.errors.stock_quantity }}</p>
                        </div>
                        <div>
                            <Label for="unit" class="mb-2 inline-block">Satuan</Label>
                            <Input id="unit" v-model="form.unit" placeholder="Contoh: gram, kg, liter, pcs" />
                            <p v-if="form.errors.unit" class="text-sm text-red-600 mt-1">{{ form.errors.unit }}</p>
                        </div>
                         <div class="col-span-2">
                            <Label for="low_stock_threshold" class="mb-2 inline-block">Ambang Batas Stok Rendah</Label>
                            <Input id="low_stock_threshold" type="number" v-model="form.low_stock_threshold" />
                            <p class="text-xs text-muted-foreground mt-1">Sistem akan memberi notifikasi jika stok mencapai angka ini.</p>
                            <p v-if="form.errors.low_stock_threshold" class="text-sm text-red-600 mt-1">{{ form.errors.low_stock_threshold }}</p>
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
                        Tindakan ini akan menghapus bahan baku <span class="font-bold">{{ ingredientToDelete?.name }}</span> secara permanen.
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel>Batal</AlertDialogCancel>
                    <AlertDialogAction @click="confirmDelete">Lanjutkan Hapus</AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </div>
</template>