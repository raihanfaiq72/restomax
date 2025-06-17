<script setup lang="ts">
    import { ref, h, computed } from 'vue';
    import type { ColumnDef } from '@tanstack/vue-table';
    import { FlexRender, getCoreRowModel, getFilteredRowModel, getPaginationRowModel, getSortedRowModel, useVueTable } from '@tanstack/vue-table';
    import { ArrowUpDown, MoreHorizontal, PlusCircle, Pencil, Trash2 } from 'lucide-vue-next';
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
    import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
    import { toast } from 'vue-sonner';

    export interface Category {
        id: number;
        name: string;
    }

    export interface Product {
        id: number;
        name: string;
        slug: string;
        sku: string;
        description: string;
        price: number;
        category_id: number;
        is_available: number;
        category: Category; 
        created_at: string;
        updated_at: string;
    }

    const props = withDefaults(defineProps<{
        products: Product[];
        categories: Category[];
    }>(),{
        products: () => [],
        categories: () => [],
    });

    const isDialogOpen = ref(false);
    const isAlertOpen = ref(false);
    const editingProduct = ref<Product | null>(null);
    const productToDelete = ref<Product | null>(null);
    const dialogTitle = computed(() => editingProduct.value ? 'Edit Produk' : 'Tambah Produk Baru');

    const form = useForm({
        name: '',
        sku: '',
        description: '',
        price: 0,
        category_id: null as number | null,
        is_available: 1, 
    });

    const openDialog = (product: Product | null) => {
        editingProduct.value = product;
        if (product) { // Mode Edit
            form.name = product.name;
            form.sku = product.sku;
            form.description = product.description;
            form.price = product.price;
            form.category_id = product.category.id;
            form.is_available = product.is_available;
        } else { // Mode Create
            form.reset();
        }
        form.clearErrors();
        isDialogOpen.value = true;
    }

    const openDeleteAlert = (product: Product) => {
        productToDelete.value = product;
        isAlertOpen.value = true; 
    }

    const onSubmit = () => {
        if (editingProduct.value) {
            form.put(route('products.update', editingProduct.value.slug), { 
                preserveScroll: true,
                onSuccess: () => {
                    isDialogOpen.value = false;
                    toast.success(`Produk "${form.name}" berhasil diperbarui.`);
                    form.reset();
                }
            }); 
        } else {
            form.post(route('products.store'), {
                preserveScroll: true,
                onSuccess: () => {
                    isDialogOpen.value = false;
                    toast.success(`Produk "${form.name}" berhasil ditambahkan.`);
                    form.reset();
                }
            });
        }
    }

    const confirmDelete = () => {
        if (productToDelete.value) {
            router.delete(route('products.destroy', productToDelete.value.slug), {
                preserveScroll: true,
                onSuccess: () => {
                    toast.error(`Produk "${productToDelete.value?.name}" telah dihapus.`);
                    productToDelete.value = null
                },
            });
        }
        isAlertOpen.value = false;
    };
    
    const columns: ColumnDef<Product>[] = [
        { id: 'select', header: ({ table }) => h(Checkbox, { checked: table.getIsAllPageRowsSelected(), 'onUpdate:checked': (value: boolean) => table.toggleAllPageRowsSelected(!!value) }), cell: ({ row }) => h(Checkbox, { checked: row.getIsSelected(), 'onUpdate:checked': (value: boolean) => row.toggleSelected(!!value) }) },
        { accessorKey: 'name', header: ({ column }) => h(Button, { variant: 'ghost', onClick: () => column.toggleSorting(column.getIsSorted() === 'asc') }, () => ['Nama Produk', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })]) },
        { accessorKey: 'sku', header: 'SKU' },
        { accessorKey: 'category', header: 'Kategori', cell: ({ row }) => row.original.category.name },
        { accessorKey: 'price', header: 'Harga', cell: ({ row }) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(row.getValue('price'))},
        { id: 'actions', cell: ({ row }) => {
            const product = row.original;
            return h('div', { class: 'relative text-right' }, h(DropdownMenu, {}, { default: () => [
                h(DropdownMenuTrigger, { asChild: true }, h(Button, { variant: 'ghost', class: 'h-8 w-8 p-0' }, () => h(MoreHorizontal, { class: 'h-4 w-4' }))),
                h(DropdownMenuContent, { align: 'end' }, [
                    h(DropdownMenuLabel, {}, 'Aksi'),
                    h(DropdownMenuItem, { class: 'flex items-center cursor-pointer', onClick: () => openDialog(product) }, () => [h(Pencil, { class: 'mr-2 h-4 w-4' }), 'Edit']),
                    h(DropdownMenuItem, { class: 'flex items-center text-red-600 cursor-pointer focus:text-red-500', onClick: () => openDeleteAlert(product) }, () => [h(Trash2, { class: 'mr-2 h-4 w-4' }), 'Hapus']),
                ]),
            ]}));
        }},
    ];

    const table = useVueTable({
        get data() { return props.products },
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
                <h2 class="text-2xl font-bold tracking-tight">Manajemen Produk</h2>
                <p class="text-muted-foreground">Kelola daftar produk yang tersedia di sini.</p>
            </div>
            <Button @click="openDialog(null)" class="flex items-center gap-2">
                <PlusCircle class="h-4 w-4" /> Tambah Produk
            </Button>
        </div>

        <div class="flex items-center justify-between py-4">
            <Input class="max-w-sm" placeholder="Filter berdasarkan nama produk..." :model-value="(table.getColumn('name')?.getFilterValue() as string) ?? ''" @update:model-value="table.getColumn('name')?.setFilterValue($event)" />
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
                            Tidak ada data produk.
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
            <DialogContent class="sm:max-w-xl">
                <DialogHeader>
                    <DialogTitle>{{ dialogTitle }}</DialogTitle>
                </DialogHeader>
                <form @submit.prevent="onSubmit">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6 py-4">
                        <div>
                            <Label for="name" class="mb-2 inline-block">Nama Produk</Label>
                            <Input id="name" v-model="form.name" />
                            <p v-if="form.errors.name" class="text-sm text-red-600 mt-1">{{ form.errors.name }}</p>
                        </div>
                        <div>
                            <Label for="sku" class="mb-2 inline-block">SKU (Stock Keeping Unit)</Label>
                            <Input id="sku" v-model="form.sku" />
                            <p v-if="form.errors.sku" class="text-sm text-red-600 mt-1">{{ form.errors.sku }}</p>
                        </div>
                        <div>
                            <Label for="category" class="mb-2 inline-block">Kategori</Label>
                            <Select v-model="form.category_id!">
                                <SelectTrigger><SelectValue placeholder="Pilih kategori" /></SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="category in props.categories" :key="category.id" :value="String(category.id)">
                                            {{ category.name }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.category_id" class="text-sm text-red-600 mt-1">Kategori wajib diisi.</p>
                        </div>
                        <div>
                            <Label for="price" class="mb-2 inline-block">Harga</Label>
                            <Input id="price" type="number" v-model="form.price" />
                            <p v-if="form.errors.price" class="text-sm text-red-600 mt-1">{{ form.errors.price }}</p>
                        </div>
                         <div class="md:col-span-2">
                            <Label for="description" class="mb-2 inline-block">Deskripsi</Label>
                            <Textarea id="description" v-model="form.description" />
                            <p v-if="form.errors.description" class="text-sm text-red-600 mt-1">{{ form.errors.description }}</p>
                        </div>
                        <div class="md:col-span-2 flex items-center space-x-2">
                            <Checkbox id="is_available" :checked="form.is_available === 1" @update:checked="(value) => form.is_available = value ? 1 : 0" />
                            <label for="is_available" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                Tersedia untuk dijual
                            </label>
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
                        Tindakan ini akan menghapus produk <span class="font-bold">{{ productToDelete?.name }}</span> secara permanen.
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