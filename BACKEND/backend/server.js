import express from 'express';
import cors from 'cors';
import paymentRoutes from './routes/payment.js';

const app = express();
app.use(express.json());
app.use(cors());

app.use('/api/payment', paymentRoutes);

const PORT = process.env.PORT || 5000;
app.listen(PORT,()=>{
    console.log("server running");
})