import express from 'express';
import Razorpay from 'razorpay';
import crypto from 'crypto';
import { savePayment } from '../models/payment.js';
import dotenv from 'dotenv';

dotenv.config();

const router = express.Router();
const razorpayInstance = new Razorpay({
    key_id: process.env.RAZORPAY_KEY_ID,
    key_secret: process.env.RAZORPAY_SECRET,
});

// Endpoint to create a Razorpay order
router.post('/order', async (req, res) => {
    const { amount } = req.body;

    try {
        const options = {
            amount: amount * 100, // Convert amount to paise
            currency: "INR",
            receipt: crypto.randomBytes(10).toString("hex"),
        };

        // Create Razorpay order
        razorpayInstance.orders.create(options, (error, order) => {
            if (error) {
                console.error("Error creating order:", error);
                return res.status(500).json({ message: "Error creating order", error });
            }
            res.status(200).json({ data: order });
        });
    } catch (error) {
        console.error("Internal server error:", error);
        res.status(500).json({ message: "Internal server error" });
    }
});

// Endpoint to verify the Razorpay payment signature
router.post('/verify', async (req, res) => {
    const { razorpay_order_id, razorpay_payment_id, razorpay_signature } = req.body;

    try {
        // Generate the expected signature
        const generatedSignature = crypto
            .createHmac("sha256", process.env.RAZORPAY_SECRET)
            .update(`${razorpay_order_id}|${razorpay_payment_id}`)
            .digest("hex");

        // Compare the generated signature with the received signature
        if (generatedSignature === razorpay_signature) {
            await savePayment({ razorpay_order_id, razorpay_payment_id, razorpay_signature });
            return res.status(200).json({ message: "Payment verified successfully" });
        }

        // Signature mismatch
        res.status(400).json({ message: "Payment verification failed" });
    } catch (error) {
        console.error("Error verifying payment:", error);
        res.status(500).json({ message: "Internal server error" });
    }
});

export default router;
