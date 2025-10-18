<script setup lang="ts">

import { HeadingSmall } from "@/components";
import { FormControl, FormPassword, FormProvider } from "@/components/forms";
import { Button } from "@/components/ui/button";
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger, } from "@/components/ui/dialog";
import { FieldGroup, FieldSet } from "@/components/ui/field";
import { Separator } from "@/components/ui/separator";
import { Spinner } from "@/components/ui/spinner";
import { AccountLayout, AppLayout } from "@/layouts";
import { type BreadcrumbItem } from "@/types";
import { Link, useForm, usePage } from "@inertiajs/vue3";

const title = "Mein Konto";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: title,
        href: route("account.edit"),
    },
];

const user = usePage().props.user;

const form = useForm({
    name: user.name,
    email: user.email,
});

const deleteAccountForm = useForm({
    password: "",
});

const submit = () => form.patch(route("account.update"), {
    preserveState: false,
});

const deleteAccount = () => deleteAccountForm.delete(route("account.destroy"));

</script>

<template>

    <AppLayout :breadcrumbs :title>
        <AccountLayout active="account">
            <FormProvider :form>
                <form @submit.prevent="submit">
                    <FieldSet>
                        <FieldGroup>
                            <FormControl name="name" label="Name" required />
                            <FormControl name="email" label="E-Mail" type="email" required />
                            <Button class="self-start" type="submit" :disabled="form.processing">
                                Speichern
                                <Spinner v-if="form.processing" />
                            </Button>
                        </FieldGroup>
                    </FieldSet>
                </form>
            </FormProvider>
            <div v-if="!user.email_verified_at" class="text-sm text-muted-foreground flex flex-col gap-1 items-start">
                Ihre E-Mail-Adresse ist noch nicht verifiziert.
                <Link href="#" class="text-foreground underline decoration-neutral-300 underline-offset-4">
                    Klicken Sie hier, um die Bestätigungs-E-Mail erneut zu senden.
                </Link>
            </div>
            <Separator />
            <div class="flex flex-col gap-4">
                <div class="flex flex-col gap-2">
                    <HeadingSmall title="Konto löschen">
                        <template #description>
                            Löschen Sie Ihr Konto sowie alle damit verbundenen Ressourcen.
                            Bitte beachten Sie, dass dieser Vorgang nicht rückgängig gemacht werden kann.
                        </template>
                    </HeadingSmall>
                </div>
                <Dialog>
                    <DialogTrigger as-child>
                        <Button variant="destructive" class="self-start">
                            Konto löschen
                        </Button>
                    </DialogTrigger>
                    <DialogContent>
                        <FormProvider :form="deleteAccountForm">
                            <form @submit.prevent="deleteAccount">
                                <DialogHeader>
                                    <DialogTitle>
                                        Sind Sie sicher, dass Sie Ihr Konto löschen möchten?
                                    </DialogTitle>
                                    <DialogDescription>
                                        Sobald Ihr Konto gelöscht wird, werden auch alle zugehörigen Ressourcen und Daten <strong>dauerhaft gelöscht</strong>.
                                        Bitte geben Sie Ihr Passwort ein, um zu bestätigen, dass Sie Ihr Konto <strong>endgültig löschen</strong> möchten.
                                    </DialogDescription>
                                </DialogHeader>
                                <FieldSet class="py-4">
                                    <FieldGroup>
                                        <FormPassword name="password" type="password" label="Aktuelles Passwort" required />
                                    </FieldGroup>
                                </FieldSet>
                                <DialogFooter class="gap-2">
                                    <DialogClose as-child>
                                        <Button variant="secondary">
                                            Abbrechen
                                        </Button>
                                    </DialogClose>
                                    <Button type="submit" variant="destructive" :disabled="deleteAccountForm.processing">
                                        Konto endgültig löschen
                                        <Spinner v-if="deleteAccountForm.processing" />
                                    </Button>
                                </DialogFooter>
                            </form>
                        </FormProvider>
                    </DialogContent>
                </Dialog>
            </div>
        </AccountLayout>
    </AppLayout>

</template>
