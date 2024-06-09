import { Card, CardHeader, CardContent } from "@/components/ui/card"
import { Table, TableCaption, TableHeader, TableBody, TableRow, TableHead, TableCell } from "@/components/ui/table"
import { FetchTransactionResponse, fetchTransactions } from "@/store/home"
import { Link, useLoaderData } from "react-router-dom"
import { Badge } from "@/components/ui/badge"
import { useEffect, useState } from "react"

function renderTransactionStatus(status: string) {
  switch (status) {
    case "Belum dibayar":
      return <Badge variant="secondary">{status}</Badge>
    case "Pending":
      return <Badge className="bg-yellow-500">{status}</Badge>
    case "Sukses":
      return <Badge className="bg-green-500">{status}</Badge>
    case "Gagal":
      return <Badge className="bg-red-500">{status}</Badge>
  }
}

function renderTransactionType(type: string) {
  switch (type) {
    case "Deposit":
      return <Badge className="bg-green-500">{type}</Badge>
    case "Withdraw":
      return <Badge className="bg-red-500">{type}</Badge>
  }
}

export default function Home() {
  const [transactions, setTransactions] = useState<FetchTransactionResponse["data"]>([])

  useEffect(() => {
    async function fetchData() {
      const response = await fetchTransactions()
      if (response.error) {
        alert(response.message)
        return
      }

      setTransactions(response.data)
    }

    fetchData()
  }, [])

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
                transactions.map((transaction, i) => {
                  return (
                    <TableRow key={transaction.order_id}>
                      <TableCell>{i + 1}</TableCell>
                      <TableCell>{transaction.order_id}</TableCell>
                      <TableCell>{renderTransactionType(transaction.type)}</TableCell>
                      <TableCell>{transaction.type === "Deposit" ? transaction.amount : `-${transaction.amount}`}</TableCell>
                      <TableCell>{renderTransactionStatus(transaction.status)}</TableCell>
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
