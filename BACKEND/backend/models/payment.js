import pool from '../config/db.js';

export const savePayment = async (paymentDetails) => {
    const { razorpay_order_id, razorpay_payment_id, razorpay_signature } = paymentDetails;
    const [rows] = await pool.execute(
        'INSERT INTO payments (razorpay_order_id, razorpay_payment_id, razorpay_signature) VALUES (?, ?, ?)',
        [razorpay_order_id, razorpay_payment_id, razorpay_signature]
    );
    return rows;
};