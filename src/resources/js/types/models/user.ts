export class User {

    id!: number;
    name!: string;
    email!: string;
    avatar?: string;

    constructor(data: Partial<User> = {}) {
        Object.assign(this, data);
    }
}