class CartItem:
    def __init__(self, n,p, q=1, d=0):
        self.name=n
        self.price=p
        self.quantity=q
        self.discount=d
    def __str__(self):
        return f"{self.name} {self.price} {self.quantity} {self.discount}"
    
    def get_subtotal(self):
        total=(self.price*self.quantity)-(self.price*self.quantity*self.discount)
        return total
       
class ShoppingCart:
    def __init__(self):
        self.items=[]
    def add_item(self, item):
        self.items.append(item)
    def remove_item(self, name_item):
        n=name_item.lower()
        index=-1
        for a in self.items:
            if(a.name.lower()==n):
                self.items.remove(a)
                return True
            return False
    def total(self):
        sum = 0
        for item in self.items:
            sum += item.get_subtotal()         
        return sum
         
               
    
O1=CartItem('milk', 100,2, 0.10)
O2=CartItem('tea', 50,3, 0.10)
O3=CartItem('chips', 30,5, 0.10)
O4=CartItem('coffee', 100,6, 0.10)
List=ShoppingCart()
List.add_item(O1)
List.add_item(O2)
List.add_item(O3)
List.add_item(O4)

print(List.items)
print(List.total())
# print(O1)
# sum=O1.get_subtotal()
# print("the thotal is", sum, '$')