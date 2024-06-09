import { Card, CardHeader, CardContent } from "@/components/ui/card"
import { Table, TableCaption, TableHeader, TableBody, TableRow, TableHead, TableCell } from "@/components/ui/table"
import { FetchTransactionResponse, fetchCurrentBalance, fetchTransactions } from "@/store/home"
import { Link } from "react-router-dom"
import { Badge } from "@/components/ui/badge"
import { useEffect, useRef, useState } from "react"
import { cn } from "@/lib/utils"

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

function renderAmount(transactionType: string, amount: string) {
  let className = ""

  switch (transactionType) {
    case "Deposit":
      className = "text-green-500"
      break
    case "Withdraw":
      className = "text-red-500"
      amount = `-${amount}`
      break
  }

  const formattedAmount = new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR"
  }).format(Number(amount))

  return <p className={className}>{formattedAmount}</p>
}

export default function Home() {
  const [homeData, setHomeData] = useState<{ transactions: FetchTransactionResponse["data"], currentBalance: string }>({
    transactions: [],
    currentBalance: ""
  })
  const didMount = useRef<boolean>(false)

  useEffect(() => {
    async function fetchData() {
      const fetechTransactionsResponse = await fetchTransactions()
      if (fetechTransactionsResponse.error) {
        alert(fetechTransactionsResponse.message)
        return
      }

      const fetchCurrentBalanceResponse = await fetchCurrentBalance()
      if (fetchCurrentBalanceResponse.error) {
        alert(fetchCurrentBalanceResponse.message)
        return
      }

      setHomeData((prev) => {
        return {
          ...prev,
          transactions: fetechTransactionsResponse.data,
          currentBalance: new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR"
          }).format(Number(fetchCurrentBalanceResponse.data.current_balance)),
        }
      })
    }

    if (!didMount.current) {
      fetchData()
    }
    didMount.current = true
  }, [])

  return (
    <>
      <Card className="w-full text-center">
        <CardHeader>
          <div className="flex justify-center align-center gap-4">
            <h1 className="text-3xl font-bold">Sisa Saldo:</h1>
            <h1 className="text-3xl font-bold text-green-500">{homeData.currentBalance}</h1>
          </div>
        </CardHeader>
        <CardContent className="mt-4">
          <div className={cn(
            "grid",
            "md:grid-cols-8 md:gap-4",
            "grid-rows-2 gap-4"
          )}>
            <Link to="/deposit" className={cn(
              "p-4 bg-green-300 rounded-lg hover:bg-green-200",
              "md:col-start-2 md:col-end-4"
            )}>Deposit</Link>
            <Link to="/withdraw" className={cn(
              "p-4 bg-red-300 rounded-lg hover:bg-red-200",
              "md:col-start-6 md:col-end-8"
            )}>Withdraw</Link>
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
                homeData.transactions.map((transaction, i) => {
                  return (
                    <TableRow key={transaction.order_id}>
                      <TableCell>{i + 1}</TableCell>
                      <TableCell>{transaction.order_id}</TableCell>
                      <TableCell>{renderTransactionType(transaction.type)}</TableCell>
                      <TableCell>{renderAmount(transaction.type, transaction.amount)}</TableCell>
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
