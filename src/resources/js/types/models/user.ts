export class User {

    id!: number;
    name!: string;
    email!: string;
    avatar?: string;
    email_verified_at?: string;
    created_at!: string;
    updated_at!: string;

    constructor(data: Partial<User> = {}) {
        Object.assign(this, data);
    }
}