<script setup lang="ts">
    import { ref, h, computed } from 'vue';
    import type { ColumnDef } from '@tanstack/vue-table';
    import { FlexRender, getCoreRowModel, getFilteredRowModel, getPaginationRowModel, getSortedRowModel, useVueTable } from '@tanstack/vue-table';
    import { ArrowUpDown, MoreHorizontal, PlusCircle, Pencil, Trash2, Crown, Medal, Gem } from 'lucide-vue-next';
    import { useForm, router } from '@inertiajs/vue3';
    import { Button } from '@/components/ui/button';
    import { Checkbox } from '@/components/ui/checkbox';
    import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
    import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
    import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle } from '@/components/ui/alert-dialog';
    import { Input } from '@/components/ui/input';
    import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
    import { Label } from '@/components/ui/label';
    import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
    import { toast } from 'vue-sonner';
    import { Badge } from '@/components/ui/badge';

    export interface Customer {
        id: number;
        name: string;
        slug: string;
        phone_number: string;
        email: string;
        birth_date: string;
        loyalty_tier: 'bronze' | 'silver' | 'gold';
        loyality_points: number;
        created_at: string;
        updated_at: string;
    }

    const props = withDefaults(defineProps<{
        customers: Customer[];
    }>(),{
        customers: () => [],
    });

    const isDialogOpen = ref(false);
    const isAlertOpen = ref(false);
    const editingCustomer = ref<Customer | null>(null);
    const customerToDelete = ref<Customer | null>(null);
    const dialogTitle = computed(() => editingCustomer.value ? 'Edit Pelanggan' : 'Tambah Pelanggan Baru');

    const form = useForm({
        name: '',
        phone_number: '',
        email: '',
        birth_date: '',
        loyalty_tier: 'bronze' as 'bronze' | 'silver' | 'gold',
        loyality_points: 0,
    });

    const openDialog = (customer: Customer | null) => {
        editingCustomer.value = customer;
        if (customer) {
            form.name = customer.name;
            form.phone_number = customer.phone_number;
            form.email = customer.email;
            form.birth_date = customer.birth_date.substring(0, 10);
            form.loyalty_tier = customer.loyalty_tier;
            form.loyality_points = customer.loyality_points;
        } else {
            form.reset();
        }
        form.clearErrors();
        isDialogOpen.value = true;
    }

    const openDeleteAlert = (customer: Customer) => {
        customerToDelete.value = customer;
        isAlertOpen.value = true;
    }

    const onSubmit = () => {
        if (editingCustomer.value) {
            form.put(route('customers.update', editingCustomer.value.slug), {
                preserveScroll: true,
                onSuccess: () => {
                    isDialogOpen.value = false;
                    toast.success(`Data pelanggan "${form.name}" berhasil diperbarui.`);
                    form.reset();
                }
            });
        } else {
            form.post(route('customers.store'), {
                preserveScroll: true,
                onSuccess: () => {
                    isDialogOpen.value = false;
                    toast.success(`Pelanggan "${form.name}" berhasil ditambahkan.`);
                    form.reset();
                }
            });
        }
    }

    const confirmDelete = () => {
        if (customerToDelete.value) {
            router.delete(route('customers.destroy', customerToDelete.value.slug), {
                preserveScroll: true,
                onSuccess: () => {
                    toast.error(`Pelanggan "${customerToDelete.value?.name}" telah dihapus.`);
                    customerToDelete.value = null
                },
            });
        }
        isAlertOpen.value = false;
    };

    const loyaltyTierMap = {
        bronze: { label: 'Bronze', icon: Medal, color: 'bg-orange-600' },
        silver: { label: 'Silver', icon: Crown, color: 'bg-slate-400' },
        gold: { label: 'Gold', icon: Gem, color: 'bg-yellow-500' },
    };

    const columns: ColumnDef<Customer>[] = [
        { id: 'select', header: ({ table }) => h(Checkbox, { checked: table.getIsAllPageRowsSelected(), 'onUpdate:checked': (value: boolean) => table.toggleAllPageRowsSelected(!!value) }), cell: ({ row }) => h(Checkbox, { checked: row.getIsSelected(), 'onUpdate:checked': (value: boolean) => row.toggleSelected(!!value) }) },
        { accessorKey: 'name', header: ({ column }) => h(Button, { variant: 'ghost', onClick: () => column.toggleSorting(column.getIsSorted() === 'asc') }, () => ['Nama Pelanggan', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })]) },
        { accessorKey: 'email', header: 'Email' },
        { accessorKey: 'phone_number', header: 'No. Telepon' },
        { accessorKey: 'loyalty_tier', header: 'Tier Loyalitas',
            cell: ({ row }) => {
                const tier = row.original.loyalty_tier;
                const tierInfo = loyaltyTierMap[tier];
                return h(Badge, { class: `${tierInfo.color} text-white` }, () => [
                    h(tierInfo.icon, { class: 'mr-2 h-4 w-4' }),
                    tierInfo.label,
                ]);
            }
        },
        { accessorKey: 'loyality_points', header: 'Poin' },
        { id: 'actions', cell: ({ row }) => h('div', { class: 'relative text-right' }, h(DropdownMenu, {}, { default: () => [
            h(DropdownMenuTrigger, { asChild: true }, h(Button, { variant: 'ghost', class: 'h-8 w-8 p-0' }, () => h(MoreHorizontal, { class: 'h-4 w-4' }))),
            h(DropdownMenuContent, { align: 'end' }, [
                h(DropdownMenuLabel, {}, 'Aksi'),
                h(DropdownMenuItem, { class: 'flex items-center cursor-pointer', onClick: () => openDialog(row.original) }, () => [h(Pencil, { class: 'mr-2 h-4 w-4' }), 'Edit']),
                h(DropdownMenuItem, { class: 'flex items-center text-red-600 cursor-pointer focus:text-red-500', onClick: () => openDeleteAlert(row.original) }, () => [h(Trash2, { class: 'mr-2 h-4 w-4' }), 'Hapus']),
            ]),
        ]}))},
    ];

    const table = useVueTable({
        get data() { return props.customers },
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
                <h2 class="text-2xl font-bold tracking-tight">Manajemen Pelanggan</h2>
                <p class="text-muted-foreground">Kelola data dan poin loyalitas pelanggan Anda.</p>
            </div>
            <Button @click="openDialog(null)" class="flex items-center gap-2">
                <PlusCircle class="h-4 w-4" /> Tambah Pelanggan
            </Button>
        </div>

        <div class="flex items-center justify-between py-4">
            <Input class="max-w-sm" placeholder="Filter berdasarkan nama pelanggan..." :model-value="(table.getColumn('name')?.getFilterValue() as string) ?? ''" @update:model-value="table.getColumn('name')?.setFilterValue($event)" />
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
                            Tidak ada data pelanggan.
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
            <DialogContent class="sm:max-w-2xl">
                <DialogHeader>
                    <DialogTitle>{{ dialogTitle }}</DialogTitle>
                </DialogHeader>
                <form @submit.prevent="onSubmit">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6 py-4">
                        <div class="md:col-span-2">
                            <Label for="name" class="mb-2 inline-block">Nama Lengkap</Label>
                            <Input id="name" v-model="form.name" />
                            <p v-if="form.errors.name" class="text-sm text-red-600 mt-1">{{ form.errors.name }}</p>
                        </div>
                        <div>
                            <Label for="email" class="mb-2 inline-block">Alamat Email</Label>
                            <Input id="email" type="email" v-model="form.email" />
                            <p v-if="form.errors.email" class="text-sm text-red-600 mt-1">{{ form.errors.email }}</p>
                        </div>
                        <div>
                            <Label for="phone_number" class="mb-2 inline-block">No. Telepon</Label>
                            <Input id="phone_number" v-model="form.phone_number" />
                            <p v-if="form.errors.phone_number" class="text-sm text-red-600 mt-1">{{ form.errors.phone_number }}</p>
                        </div>
                        <div>
                            <Label for="birth_date" class="mb-2 inline-block">Tanggal Lahir</Label>
                            <Input id="birth_date" type="date" v-model="form.birth_date" />
                            <p v-if="form.errors.birth_date" class="text-sm text-red-600 mt-1">{{ form.errors.birth_date }}</p>
                        </div>
                         <div>
                            <Label for="loyalty_tier" class="mb-2 inline-block">Tier Loyalitas</Label>
                            <Select v-model="form.loyalty_tier">
                                <SelectTrigger><SelectValue placeholder="Pilih Tier" /></SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="bronze">Bronze</SelectItem>
                                        <SelectItem value="silver">Silver</SelectItem>
                                        <SelectItem value="gold">Gold</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.loyalty_tier" class="text-sm text-red-600 mt-1">{{ form.errors.loyalty_tier }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <Label for="loyality_points" class="mb-2 inline-block">Poin Loyalitas</Label>
                            <Input id="loyality_points" type="number" v-model="form.loyality_points" />
                            <p v-if="form.errors.loyality_points" class="text-sm text-red-600 mt-1">{{ form.errors.loyality_points }}</p>
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
                        Tindakan ini akan menghapus pelanggan <span class="font-bold">{{ customerToDelete?.name }}</span> secara permanen.
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