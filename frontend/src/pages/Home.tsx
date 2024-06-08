import { Card, CardHeader, CardContent } from "@/components/ui/card"
import { Table, TableCaption, TableHeader, TableBody, TableRow, TableHead, TableCell } from "@/components/ui/table"
import { FetchTransactionResponse } from "@/store/home"
import { Link, useLoaderData } from "react-router-dom"

export default function Home() {
  const response = useLoaderData() as FetchTransactionResponse

  if (response.error) {
    alert(response.message)
  }

  return (
    <>
      <Card className="w-full text-center">
        <CardHeader>
          <h1 className="text-3xl font-bold">Sisa Saldo</h1>
        </CardHeader>
        <CardContent className="mt-4">
          <div className="grid grid-cols-8 gap-4 ">
            <Link to="/deposit" className="p-4 bg-green-300 col-start-2 col-end-4 rounded-lg hover:bg-green-200">Deposit</Link>
            <Link to="/withdraw" className="p-4 bg-red-300 col-start-6 col-end-8 rounded-lg hover:bg-red-200">Withdraw</Link>
          </div>

          <Table className="mt-8 border rounded-lg">
            <TableCaption>Riwayat transaksi anda</TableCaption>

            <TableHeader>
              <TableRow>
                <TableHead className="text-center">No.</TableHead>
                <TableHead className="text-center">Kode Transaksi</TableHead>
                <TableHead className="text-center">Tipe</TableHead>
                <TableHead className="text-center">Jumlah</TableHead>
                <TableHead className="text-center">Status</TableHead>
                <TableHead className="text-center">Waktu</TableHead>
              </TableRow>
            </TableHeader>

            <TableBody>
              {
                response.data.map((transaction, i) => {
                  return (
                    <TableRow key={transaction.order_id}>
                      <TableCell>{i + 1}</TableCell>
                      <TableCell>{transaction.order_id}</TableCell>
                      <TableCell>{transaction.type}</TableCell>
                      <TableCell>{transaction.amount}</TableCell>
                      <TableCell>{transaction.status}</TableCell>
                      <TableCell>{transaction.created_at}</TableCell>
                    </TableRow>
                  )
                })
              }
            </TableBody>
          </Table>
        </CardContent>
      </Card>
    </>
  )
}
