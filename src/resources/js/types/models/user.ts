export class User {

    id!: number;
    name!: string;
    email!: string;

    constructor(data: Partial<User> = {}) {
        Object.assign(this, data);
    }
}