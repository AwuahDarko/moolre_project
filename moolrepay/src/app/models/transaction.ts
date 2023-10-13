

export class Transaction{

    public date:string;
    public email?:string;
    public phone?:string;
    public amount: string;
    public transactionId:string;
    public status:string;

    constructor(data: any) {
        this.date = data['date'];
        this.amount = data['amount']
        this.transactionId=data["transactionId"];
        this.status = data["status"]
        this.email = data["email"]
        this.phone = data["phone"]
    }
    
}